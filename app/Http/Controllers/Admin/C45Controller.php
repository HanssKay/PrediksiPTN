<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatasetPrediksi;
use App\Models\C45Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;

class C45Controller extends Controller
{
    /**
     * Memastikan server Python Flask aktif jika menggunakan alamat lokal (127.0.0.1 / localhost)
     */
    private function ensureFlaskApiRunning($apiUrl)
    {
        $parsed = parse_url($apiUrl);
        $host = $parsed['host'] ?? '127.0.0.1';
        $port = $parsed['port'] ?? 5000;

        // Hanya jalankan auto-start jika target adalah host lokal
        if (!in_array($host, ['127.0.0.1', 'localhost', '::1'])) {
            return;
        }

        // Cek apakah port sudah aktif
        $fp = @fsockopen($host, $port, $errno, $errstr, 0.3);
        if ($fp) {
            fclose($fp);
            return; // Server sudah aktif
        }

        // Jika belum aktif, jalankan di background
        $pythonPath = env('PYTHON_PATH', 'python');
        $scriptPath = base_path('python_api/flask_app.py');
        $logPath = storage_path('logs/flask.log');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows: Jalankan di background tanpa memblokir request PHP, output diarahkan ke flask.log
            pclose(popen("start /B \"\" \"{$pythonPath}\" \"{$scriptPath}\" > \"{$logPath}\" 2>&1", "r"));
        } else {
            // Linux/macOS
            exec("{$pythonPath} \"{$scriptPath}\" > \"{$logPath}\" 2>&1 &");
        }

        // Beri jeda hingga server siap (maksimal 3 detik)
        for ($i = 0; $i < 15; $i++) {
            usleep(200000); // 200 ms
            $fp = @fsockopen($host, $port, $errno, $errstr, 0.3);
            if ($fp) {
                fclose($fp);
                break;
            }
        }
    }

    /**
     * Memanggil API Python (Flask)
     */
    private function callPythonApi($endpoint, $data = [])
    {
        $apiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:5000');
        $this->ensureFlaskApiRunning($apiUrl);

        $url = rtrim($apiUrl, '/') . $endpoint;

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(120)->post($url, $data);
            if ($response->successful()) {
                return $response->json();
            }
            return ['success' => false, 'message' => 'API Error: ' . $response->status() . ' - ' . $response->body()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Gagal menghubungi API Python: ' . $e->getMessage()];
        }
    }

    private function getRawData()
    {
        return DatasetPrediksi::all()->toArray();
    }

    private function getPreprocessedData()
    {
        $path = storage_path('app/c45_preprocessed.json');
        if (file_exists($path)) {
            $json = json_decode(file_get_contents($path), true);
            return $json['data'] ?? [];
        }
        return [];
    }

    /**
     * Menjalankan seluruh pipeline C4.5 secara otomatis:
     * Preprocessing → Training → Testing
     */
    private function runFullPipeline()
    {
        $pipelineLog = [];

        // Step 1: Preprocessing
        $rawData = $this->getRawData();
        $prepResult = $this->callPythonApi('/api/preprocess', $rawData);
        
        if ($prepResult && ($prepResult['success'] ?? false)) {
            file_put_contents(storage_path('app/c45_preprocessed.json'), json_encode($prepResult, JSON_PRETTY_PRINT));
            $pipelineLog[] = '✅ Preprocessing berhasil (' . ($prepResult['total'] ?? 0) . ' data diproses)';
        } else {
            $pipelineLog[] = '❌ Preprocessing gagal: ' . ($prepResult['message'] ?? 'Unknown error');
            return implode(' | ', $pipelineLog);
        }

        // Step 2: Training C4.5
        $trainResult = $this->callPythonApi('/api/train', $prepResult['data']);
        if ($trainResult && ($trainResult['success'] ?? false)) {
            // Simpan model & steps
            file_put_contents(storage_path('app/c45_model.json'), json_encode($trainResult['model_data'], JSON_PRETTY_PRINT));
            file_put_contents(storage_path('app/c45_steps.json'), json_encode($trainResult['steps'], JSON_PRETTY_PRINT));
            if (!empty($trainResult['csv_content'])) {
                file_put_contents(storage_path('app/oversampled_dataset.csv'), $trainResult['csv_content']);
            }

            // Simpan rules ke database
            C45Rule::truncate();
            $batch = time();
            foreach ($trainResult['rules'] as $rule) {
                C45Rule::create([
                    'rule_string' => $rule['rule_string'],
                    'hasil_prediksi' => $rule['result'],
                    'batch_training' => $batch,
                ]);
            }
            $pipelineLog[] = '✅ Training C4.5 berhasil (' . count($trainResult['rules']) . ' rules)';
        } else {
            $pipelineLog[] = '❌ Training gagal: ' . ($trainResult['message'] ?? 'Unknown error');
            return implode(' | ', $pipelineLog);
        }

        // Step 3: Testing (dengan split ratio default 80%)
        $testPayload = [
            'data' => $prepResult['data'],
            'method' => 'random',
            'arg1' => 80
        ];
        $testResult = $this->callPythonApi('/api/test', $testPayload);
        if ($testResult && ($testResult['success'] ?? false)) {
            file_put_contents(storage_path('app/c45_test_result.json'), json_encode($testResult, JSON_PRETTY_PRINT));
            $accuracy = $testResult['metrics']['accuracy'] ?? 0;
            $pipelineLog[] = '✅ Pengujian berhasil (Akurasi: ' . $accuracy . '%)';
        } else {
            $pipelineLog[] = '⚠️ Pengujian gagal: ' . ($testResult['message'] ?? 'Unknown error') . ' (tidak fatal)';
        }

        return implode(' | ', $pipelineLog);
    }

    /**
     * Sub-menu: Input Dataset
     */
    public function dataset()
    {
        $datasets = DatasetPrediksi::latest()->paginate(10);
        $totalData = DatasetPrediksi::count();
        $totalLolos = DatasetPrediksi::where('status', 'LOLOS')->count();
        $totalTidakLolos = DatasetPrediksi::where('status', 'TIDAK LOLOS')->count();

        return view('admin.c45.dataset', compact('datasets', 'totalData', 'totalLolos', 'totalTidakLolos'));
    }

    /**
     * Import file Excel ke dataset
     * Otomatis mendeteksi dan memetakan header Excel ke kolom database
     */
    public function importDataset(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'tahun_lulus' => 'required|numeric|min:2000|max:2099',
        ]);

        try {
            $tahunLulus = $request->input('tahun_lulus');
            $file = $request->file('file_excel');
            $ext = $file->getClientOriginalExtension();
            
            $reader = \Spatie\SimpleExcel\SimpleExcelReader::create($file->getRealPath(), $ext)->noHeaderRow();
            
            $rows = [];
            $idx = 1;
            $reader->getRows()->each(function(array $rowProperties) use (&$rows, &$idx) {
                $rows[$idx] = $rowProperties;
                $idx++;
            });

            // Cari baris header secara otomatis (baris pertama yang punya banyak isi)
            // Excel sering punya judul/merged cells di baris atas
            $headers = null;
            $headerRowIndex = 0;
            foreach ($rows as $idx => $row) {
                $nonEmpty = array_filter($row, fn($v) => $v !== null && trim(strval($v)) !== '');
                if (count($nonEmpty) >= 5) { // Header biasanya punya banyak kolom terisi
                    $headers = $row;
                    $headerRowIndex = $idx;
                    break;
                }
            }

            if (!$headers) {
                return back()->with('error', 'Tidak ditemukan baris header yang valid di file Excel.');
            }

            // Ambil data setelah baris header
            $dataRows = array_slice($rows, $headerRowIndex - array_key_first($rows) + 1);

            // Normalisasi header: lowercase, trim, hapus spasi/karakter khusus
            $headerMap = [];
            foreach ($headers as $col => $name) {
                if ($name !== null && trim($name) !== '') {
                    $normalized = strtolower(trim($name));
                    $normalized = preg_replace('/[\s\-\.]+/', '_', $normalized);  // spasi/dash/dot -> underscore
                    $normalized = preg_replace('/[^a-z0-9_]/', '', $normalized); // hapus karakter non-alfanumerik
                    $headerMap[$col] = $normalized;
                }
            }

            // Daftar kolom database
            $dbColumns = \Illuminate\Support\Facades\Schema::getColumnListing('dataset_prediksi');

            // Buat mapping: header Excel -> kolom database
            $columnMapping = [];
            foreach ($headerMap as $col => $header) {
                // 1. Exact match langsung
                if (in_array($header, $dbColumns)) {
                    $columnMapping[$col] = $header;
                    continue;
                }

                // 2. Alias mapping untuk kolom umum
                $aliases = [
                    'nama_siswa' => ['nama', 'nama_alumni', 'nama_siswa', 'name', 'nama_lengkap', 'siswa'],
                    'nisn' => ['nisn', 'nomor_induk_siswa_nasional'],
                    'rank_paralel' => ['rank_paralel', 'rank', 'ranking', 'ranking_paralel', 'peringkat'],
                    'pilihan_1_univ' => ['pilihan_1_univ', 'univ', 'universitas', 'ptn', 'univ_1', 'pilihan1_univ', 'ptn_1', 'pil1_universitas'],
                    'pilihan_1_prodi' => ['pilihan_1_prodi', 'prodi', 'prodi_1', 'jurusan', 'pilihan1_prodi', 'prodi_pilihan_1', 'pil1_jurusan', 'pil1_prodi'],
                    'pil1_keketatan' => ['pil1_keketatan', 'pil1_keketan', 'keketatan_1', 'keketatan_pil1', 'pil1_keketatan'],
                    'pilihan_2_univ' => ['pilihan_2_univ', 'univ_2', 'ptn_2', 'pilihan2_univ', 'pil2_universitas'],
                    'pilihan_2_prodi' => ['pilihan_2_prodi', 'prodi_2', 'pilihan2_prodi', 'prodi_pilihan_2', 'pil2_jurusan', 'pil2_prodi'],
                    'pil2_keketatan' => ['pil2_keketatan', 'pil2_keketan', 'keketatan_2', 'keketatan_pil2', 'pil2_keketatan'],
                    'prestasi_tertinggi' => ['prestasi_tertinggi', 'prestasi', 'prestasi_terbaik'],
                    'rata_keseluruhan' => ['rata_keseluruhan', 'rata_total', 'ratarata_keseluruhan', 'rata_rata_keseluruhan', 'total_rata', 'rata2_keseluruhan', 'ratarata_total'],
                    'status' => ['status', 'hasil', 'kelulusan', 'status_kelulusan', 'hasil_seleksi', 'keterangan', 'status_akhir'],
                ];

                $matched = false;
                foreach ($aliases as $dbCol => $aliasList) {
                    if (in_array($header, $aliasList)) {
                        $columnMapping[$col] = $dbCol;
                        $matched = true;
                        break;
                    }
                }
                if ($matched) continue;

                // 3. Pattern match untuk kolom nilai mapel (misal: bind_smt1, bind_smt_1, bind_semester_1)
                $mapel = ['bind', 'bing', 'mtk_wajib', 'mtk_lanjut', 'fisika', 'kimia', 'biologi'];

                foreach ($mapel as $m) {
                    for ($i = 1; $i <= 5; $i++) {
                        $patterns = [
                            "{$m}_smt{$i}",
                            "{$m}_smt_{$i}",
                            "{$m}_semester_{$i}",
                            "{$m}_semester{$i}",
                            "{$m}{$i}",
                            "{$m}_s{$i}",
                            "{$m}_smr{$i}", // typo support
                        ];
                        if (in_array($header, $patterns)) {
                            $columnMapping[$col] = "{$m}_smt{$i}";
                            break 2;
                        }
                    }
                }
            }

            // Log headers untuk debugging
            $headerDebug = [];
            foreach ($headerMap as $col => $header) {
                $mapped = $columnMapping[$col] ?? '❌ TIDAK DIKENALI';
                $headerDebug[] = "{$header} → {$mapped}";
            }

            /**
             * Helper: Cek apakah teks status menunjukkan LOLOS
             * Penting: cek frasa negatif DULU sebelum cek positif,
             * karena "TIDAK LULUS" mengandung kata "LULUS" di dalamnya.
             */
            $isStatusLolos = function ($statusText) {
                $statusText = strtoupper(trim($statusText));
                if ($statusText === '' || $statusText === '-' || $statusText === '0') {
                    return false;
                }

                // Cek frasa negatif terlebih dahulu
                $tidakLolosPatterns = ['TIDAK LOLOS', 'TIDAK LULUS', 'TIDAK DITERIMA', 'GAGAL', 'DITOLAK'];
                foreach ($tidakLolosPatterns as $pattern) {
                    if (strpos($statusText, $pattern) !== false) {
                        return false;
                    }
                }

                // Baru cek frasa positif
                $lolosPatterns = ['LOLOS', 'LULUS', 'DITERIMA'];
                foreach ($lolosPatterns as $pattern) {
                    if (strpos($statusText, $pattern) !== false) {
                        return true;
                    }
                }

                return false;
            };

            $imported = 0;
            $skipped = 0;
            $debugRows = [];

            foreach ($dataRows as $row) {
                // Skip baris kosong
                $nonEmpty = array_filter($row, fn($v) => $v !== null && $v !== '');
                if (count($nonEmpty) < 3) {
                    $skipped++;
                    continue;
                }

                $data = [];
                $statusP1 = '';
                $statusP2 = '';

                // Map kolom Excel ke kolom database
                foreach ($columnMapping as $col => $dbCol) {
                    $cellValue = $row[$col] ?? null;
                    if ($cellValue === null || $cellValue === '') continue;

                    // Kolom numerik (nilai mapel, rata-rata)
                    if (preg_match('/_smt\d$/', $dbCol) || $dbCol === 'rata_keseluruhan') {
                        $data[$dbCol] = is_numeric($cellValue) ? floatval($cellValue) : null;
                    }
                    // Kolom status tunggal
                    elseif ($dbCol === 'status') {
                        $data[$dbCol] = $isStatusLolos($cellValue) ? 'LOLOS' : 'TIDAK LOLOS';
                    }
                    // Simpan status pilihan sementara
                    elseif ($dbCol === 'status_pil1') {
                        $statusP1 = trim($cellValue);
                    }
                    elseif ($dbCol === 'status_pil2') {
                        $statusP2 = trim($cellValue);
                    }
                    // Kolom string lainnya
                    else {
                        $data[$dbCol] = trim($cellValue);
                    }
                }

                // Masukkan tahun lulus dari input form
                $data['tahun_lulus'] = $tahunLulus;

                // Cek status berdasarkan pilihan 1 dan pilihan 2 jika status utama belum ada
                if (!isset($data['status'])) {
                    $lolosPil1 = $isStatusLolos($statusP1);
                    $lolosPil2 = $isStatusLolos($statusP2);
                    $data['status'] = ($lolosPil1 || $lolosPil2) ? 'LOLOS' : 'TIDAK LOLOS';
                }

                // Simpan info debug untuk beberapa baris pertama
                if (count($debugRows) < 5) {
                    $debugRows[] = [
                        'nama' => $data['nama_siswa'] ?? '?',
                        'status_pil1_raw' => $statusP1,
                        'status_pil2_raw' => $statusP2,
                        'status_final' => $data['status'],
                        'univ' => $data['pilihan_1_univ'] ?? '-',
                    ];
                }

                // Default values
                if (!isset($data['pilihan_1_univ'])) $data['pilihan_1_univ'] = '-';
                if (!isset($data['pilihan_1_prodi'])) $data['pilihan_1_prodi'] = '-';

                // Daftar mapel detail untuk menghitung rata-rata semester
                $mapelDetail = ['bind', 'bing', 'mtk_wajib', 'mtk_lanjut', 'fisika', 'kimia', 'biologi'];

                // Hitung rata_keseluruhan jika belum ada
                if (!isset($data['rata_keseluruhan']) || $data['rata_keseluruhan'] == 0) {
                    $rataValues = [];
                    for ($i = 1; $i <= 5; $i++) {
                        $smtValues = [];
                        foreach ($mapelDetail as $m) {
                            $colName = "{$m}_smt{$i}";
                            if (isset($data[$colName]) && $data[$colName] > 0) {
                                $smtValues[] = $data[$colName];
                            }
                        }
                        if (count($smtValues) > 0) {
                            $rataValues[] = round(array_sum($smtValues) / count($smtValues), 2);
                        }
                    }
                    $data['rata_keseluruhan'] = count($rataValues) > 0 ? round(array_sum($rataValues) / count($rataValues), 2) : 0;
                }

                DatasetPrediksi::create($data);
                $imported++;
            }

            $msg = "Berhasil mengimport {$imported} data.";
            if ($skipped > 0) $msg .= " ({$skipped} baris dilewati)";

            // Simpan debug info ke file
            $debugInfo = "=== IMPORT DEBUG " . now() . " ===\n\n";
            $debugInfo .= "=== RAW HEADERS ===\n";
            foreach ($headers as $col => $name) {
                $debugInfo .= "Col {$col}: [{$name}]\n";
            }
            $debugInfo .= "\n=== COLUMN MAPPING ===\n";
            foreach ($columnMapping as $col => $dbCol) {
                $debugInfo .= "Col {$col} ({$headerMap[$col]}) → {$dbCol}\n";
            }
            $debugInfo .= "\n=== SAMPLE DATA (5 baris pertama) ===\n";
            foreach ($debugRows as $i => $dr) {
                $debugInfo .= "Row " . ($i + 1) . ": nama={$dr['nama']}, status_pil1_raw=[{$dr['status_pil1_raw']}], status_pil2_raw=[{$dr['status_pil2_raw']}], status_final={$dr['status_final']}, univ={$dr['univ']}\n";
            }
            $debugInfo .= "\n=== SUMMARY ===\n";
            $debugInfo .= "Imported: {$imported}, Skipped: {$skipped}\n";
            file_put_contents(storage_path('app/excel_debug.txt'), $debugInfo);

            // === PIPELINE OTOMATIS ===
            // Setelah import berhasil, jalankan seluruh proses C4.5 secara otomatis
            $pipelineResult = $this->runFullPipeline();
            $msg .= "\n\n🔄 Pipeline Otomatis: " . $pipelineResult;

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Hapus satu data dataset
     */
    public function destroyDataset($id)
    {
        DatasetPrediksi::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Hapus semua data dataset
     */
    public function destroyAllDataset()
    {
        DatasetPrediksi::truncate();
        C45Rule::truncate();

        // Bersihkan file hasil pipeline
        $filesToDelete = [
            storage_path('app/c45_preprocessed.json'),
            storage_path('app/c45_model.json'),
            storage_path('app/c45_steps.json'),
            storage_path('app/c45_test_result.json'),
        ];
        foreach ($filesToDelete as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        return back()->with('success', 'Semua data dan hasil perhitungan berhasil dihapus.');
    }

    /**
     * Sub-menu: Preprocessing Data
     */
    public function preprocessing()
    {
        // Baca dari file JSON yang sudah disimpan (dari pipeline otomatis)
        $preprocessedPath = storage_path('app/c45_preprocessed.json');
        $result = null;

        if (file_exists($preprocessedPath)) {
            $result = json_decode(file_get_contents($preprocessedPath), true);
        } else {
            // Jika file belum ada, coba jalankan preprocessing
            $totalData = DatasetPrediksi::count();
            if ($totalData > 0) {
                $rawData = $this->getRawData();
                $result = $this->callPythonApi('/api/preprocess', $rawData);
                if ($result && ($result['success'] ?? false)) {
                    file_put_contents($preprocessedPath, json_encode($result, JSON_PRETTY_PRINT));
                }
            }
        }

        return view('admin.c45.preprocessing', compact('result'));
    }

    /**
     * POST: Refresh/re-run preprocessing secara manual
     */
    public function refreshPreprocessing()
    {
        $totalData = DatasetPrediksi::count();
        if ($totalData == 0) {
            return redirect()->route('admin.c45.preprocessing')
                ->with('error', 'Tidak ada data dataset. Silakan import data terlebih dahulu.');
        }

        $rawData = $this->getRawData();
        $result = $this->callPythonApi('/api/preprocess', $rawData);
        
        if ($result && ($result['success'] ?? false)) {
            file_put_contents(storage_path('app/c45_preprocessed.json'), json_encode($result, JSON_PRETTY_PRINT));
            return redirect()->route('admin.c45.preprocessing')
                ->with('success', 'Preprocessing berhasil dijalankan ulang! ' . ($result['total'] ?? 0) . ' data diproses.');
        }

        return redirect()->route('admin.c45.preprocessing')
            ->with('error', 'Gagal menjalankan preprocessing: ' . ($result['message'] ?? 'Unknown error'));
    }

    /**
     * Sub-menu: Proses Perhitungan
     */
    public function perhitungan()
    {
        $stepsPath = storage_path('app/c45_steps.json');
        $modelPath = storage_path('app/c45_model.json');

        $steps = null;
        $tree = null;
        $rules = null;
        $totalData = DatasetPrediksi::count();

        if (file_exists($stepsPath)) {
            $steps = json_decode(file_get_contents($stepsPath), true);
        }
        if (file_exists($modelPath)) {
            $model = json_decode(file_get_contents($modelPath), true);
            $tree = $model['tree'] ?? null;
            $rules = $model['rules'] ?? null;
        }

        return view('admin.c45.perhitungan', compact('steps', 'tree', 'rules', 'totalData'));
    }

    /**
     * POST: Jalankan proses training C4.5
     */
    public function process()
    {
        $prepData = $this->getPreprocessedData();
        if (empty($prepData)) {
            return redirect()->route('admin.c45.perhitungan')
                ->with('error', 'Data preprocessing tidak ditemukan. Harap jalankan preprocessing terlebih dahulu.');
        }

        $result = $this->callPythonApi('/api/train', $prepData);

        if ($result && ($result['success'] ?? false)) {
            // Simpan model & steps
            file_put_contents(storage_path('app/c45_model.json'), json_encode($result['model_data'], JSON_PRETTY_PRINT));
            file_put_contents(storage_path('app/c45_steps.json'), json_encode($result['steps'], JSON_PRETTY_PRINT));
            if (!empty($result['csv_content'])) {
                file_put_contents(storage_path('app/oversampled_dataset.csv'), $result['csv_content']);
            }

            // Simpan rules ke database
            C45Rule::truncate();
            $batch = time();
            foreach ($result['rules'] as $rule) {
                C45Rule::create([
                    'rule_string' => $rule['rule_string'],
                    'hasil_prediksi' => $rule['result'],
                    'batch_training' => $batch,
                ]);
            }

            return redirect()->route('admin.c45.perhitungan')
                ->with('success', 'Proses C4.5 berhasil! Ditemukan ' . count($result['rules']) . ' aturan keputusan.');
        }

        return redirect()->route('admin.c45.perhitungan')
            ->with('error', 'Gagal menjalankan proses C4.5: ' . ($result['message'] ?? 'Unknown error'));
    }

    /**
     * Sub-menu: Pohon Keputusan
     */
    public function pohonKeputusan()
    {
        $modelPath = storage_path('app/c45_model.json');
        $tree = null;
        $rules = [];

        if (file_exists($modelPath)) {
            $model = json_decode(file_get_contents($modelPath), true);
            $tree = $model['tree'] ?? null;
            $rules = $model['rules'] ?? [];
        }

        return view('admin.c45.pohon', compact('tree', 'rules'));
    }

    /**
     * Download CSV hasil oversampling
     */
    public function downloadOversampled()
    {
        $path = storage_path('app/oversampled_dataset.csv');
        if (file_exists($path)) {
            return response()->download($path);
        }
        return back()->with('error', 'File dataset oversampling belum tersedia. Silakan jalankan Proses Perhitungan terlebih dahulu.');
    }

    /**
     * Sub-menu: Pengujian Akurasi
     */
    public function pengujian()
    {
        $totalData = DatasetPrediksi::count();
        $tahunList = DatasetPrediksi::whereNotNull('tahun_lulus')->distinct()->orderBy('tahun_lulus', 'desc')->pluck('tahun_lulus');
        
        // Baca hasil pengujian dari file JSON yang sudah disimpan
        $testResultPath = storage_path('app/c45_test_result.json');
        $result = null;

        if (file_exists($testResultPath)) {
            $result = json_decode(file_get_contents($testResultPath), true);
        }

        return view('admin.c45.pengujian', compact('result', 'totalData', 'tahunList'));
    }

    /**
     * POST: Jalankan pengujian akurasi (manual re-run)
     */
    public function runTest(Request $request)
    {
        $prepData = $this->getPreprocessedData();
        if (empty($prepData)) {
            return back()->with('error', 'Data preprocessing tidak ditemukan. Harap jalankan preprocessing terlebih dahulu.');
        }

        $method = $request->input('split_method', 'random');
        
        $testPayload = [
            'data' => $prepData,
            'method' => $method
        ];

        if ($method == 'random') {
            $testPayload['arg1'] = $request->input('split_ratio', 80);
        } else {
            $trainYears = $request->input('train_years', []);
            $testYears = $request->input('test_years', []);
            
            if(empty($trainYears) || empty($testYears)) {
                return back()->with('error', 'Pilih minimal 1 tahun untuk training dan 1 tahun untuk testing.');
            }
            
            $testPayload['arg1'] = $trainYears;
            $testPayload['arg2'] = $testYears;
        }
        
        $result = $this->callPythonApi('/api/test', $testPayload);

        if ($result && ($result['success'] ?? false)) {
            file_put_contents(storage_path('app/c45_test_result.json'), json_encode($result, JSON_PRETTY_PRINT));
        } else {
            return back()->with('error', 'Gagal pengujian: ' . ($result['message'] ?? 'Unknown error'));
        }
        
        $totalData = DatasetPrediksi::count();
        $tahunList = DatasetPrediksi::whereNotNull('tahun_lulus')->distinct()->orderBy('tahun_lulus', 'desc')->pluck('tahun_lulus');

        return view('admin.c45.pengujian', compact('result', 'totalData', 'tahunList'));
    }
}
