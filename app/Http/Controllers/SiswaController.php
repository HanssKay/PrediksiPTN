<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPrediksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    /**
     * Fungsi kategorisasi nilai (sama persis dengan preprocessing.py)
     */
    private function categorize($value)
    {
        if ($value === null || $value == 0) return 'Kurang';
        if ($value >= 90) return 'Sangat Baik';
        if ($value >= 80) return 'Baik';
        if ($value >= 70) return 'Cukup';
        return 'Kurang';
    }

    /**
     * Fungsi untuk menelusuri Pohon Keputusan C4.5
     */
    private function predict($tree, $data)
    {
        // Jika ini leaf node, kembalikan labelnya
        if (isset($tree['type']) && $tree['type'] === 'leaf') {
            return $tree['label'];
        }

        // Ambil atribut yang diuji di node ini
        $attribute = $tree['attribute'] ?? null;
        if (!$attribute || !isset($tree['children'])) {
            return 'TIDAK LOLOS'; // Default jika tree rusak
        }

        // Ambil nilai atribut dari data siswa
        $value = $data[$attribute] ?? null;

        // Normalize: lowercase untuk pencocokan
        $valueLower = strtolower((string) $value);

        // Cari child yang cocok
        if (isset($tree['children'][$value])) {
            return $this->predict($tree['children'][$value], $data);
        }
        // Coba lowercase
        if (isset($tree['children'][$valueLower])) {
            return $this->predict($tree['children'][$valueLower], $data);
        }

        // Jika tidak ada cabang yang cocok, ambil cabang dengan count terbanyak
        $maxCount = 0;
        $bestChild = null;
        foreach ($tree['children'] as $child) {
            if (isset($child['type']) && $child['type'] === 'leaf') {
                if (($child['count'] ?? 0) > $maxCount) {
                    $maxCount = $child['count'];
                    $bestChild = $child;
                }
            }
        }

        if ($bestChild) {
            return $bestChild['label'];
        }

        // Fallback: telusuri child pertama yang tersedia
        $firstChild = reset($tree['children']);
        if ($firstChild) {
            return $this->predict($firstChild, $data);
        }

        return 'TIDAK LOLOS';
    }

    /**
     * Dashboard Siswa
     */
    public function index()
    {
        $lastPrediction = RiwayatPrediksi::where('user_id', Auth::id())
            ->latest()
            ->first();

        $totalPrediksi = RiwayatPrediksi::where('user_id', Auth::id())->count();

        return view('siswa.dashboard', compact('lastPrediction', 'totalPrediksi'));
    }

    /**
     * Form Prediksi
     */
    public function formPrediksi()
    {
        $universitas = \App\Models\MasterUniversitas::with('prodis')->get();
        return view('siswa.prediksi.form', compact('universitas'));
    }

    /**
     * Proses Prediksi (Submit Form)
     */
    public function prosesPrediski(Request $request)
    {
        $request->validate([
            'bind_avg' => 'required|numeric|min:0|max:100',
            'bing_avg' => 'required|numeric|min:0|max:100',
            'mtk_wajib_avg' => 'required|numeric|min:0|max:100',
            'mtk_lanjut_avg' => 'required|numeric|min:0|max:100',
            'fisika_avg' => 'required|numeric|min:0|max:100',
            'kimia_avg' => 'required|numeric|min:0|max:100',
            'biologi_avg' => 'required|numeric|min:0|max:100',
            'univ_pil1' => 'required|string',
            'prodi_pil1' => 'required|string',
            'keketatan_pil1' => 'required|string',
            'prestasi_tertinggi' => 'required|string',
        ]);

        // Base Kategorisasi (tanpa keketatan jurusan)
        $baseKategorisasi = [
            'bind' => $this->categorize($request->bind_avg),
            'bing' => $this->categorize($request->bing_avg),
            'mtk_wajib' => $this->categorize($request->mtk_wajib_avg),
            'mtk_lanjut' => $this->categorize($request->mtk_lanjut_avg),
            'fisika' => $this->categorize($request->fisika_avg),
            'kimia' => $this->categorize($request->kimia_avg),
            'biologi' => $this->categorize($request->biologi_avg),
            'prestasi_tertinggi' => strtolower($request->prestasi_tertinggi),
        ];

        // Baca model C4.5
        $modelPath = storage_path('app/c45_model.json');
        if (!file_exists($modelPath)) {
            return back()->with('error', 'Model C4.5 belum tersedia. Hubungi Admin untuk menjalankan proses Training terlebih dahulu.');
        }

        $model = json_decode(file_get_contents($modelPath), true);
        $tree = $model['tree'] ?? null;

        if (!$tree) {
            return back()->with('error', 'Pohon Keputusan C4.5 kosong. Hubungi Admin.');
        }

        // TAHAP 1: Uji Pilihan 1 (Anggap Pilihan 2 Kosong)
        $kategorisasi1 = $baseKategorisasi;
        $kategorisasi1['pil1_keketatan'] = strtolower($request->keketatan_pil1);
        $kategorisasi1['pil2_keketatan'] = 'kosong';
        
        $hasil1 = $this->predict($tree, $kategorisasi1);
        $hasilPrediksi = 'TIDAK LOLOS';

        if (strtoupper($hasil1) === 'LOLOS') {
            $hasilPrediksi = 'LOLOS PILIHAN 1';
        } else {
            // Jika gagal di Pilihan 1, uji Pilihan 2 (jika ada)
            if ($request->keketatan_pil2) {
                // TAHAP 2: Uji Pilihan 2 
                // Catatan: Karena model mungkin sensitif pada kolom pil1_keketatan vs pil2_keketatan, 
                // kita menguji sesuai fitur yang ada di tree. Jika Pilihan 1 kita set kosong dan pilihan 2 diisi.
                $kategorisasi2 = $baseKategorisasi;
                $kategorisasi2['pil1_keketatan'] = 'kosong';
                $kategorisasi2['pil2_keketatan'] = strtolower($request->keketatan_pil2);
                
                $hasil2 = $this->predict($tree, $kategorisasi2);
                
                if (strtoupper($hasil2) === 'LOLOS') {
                    $hasilPrediksi = 'LOLOS PILIHAN 2';
                }
            }
        }

        // LOGIKA REKOMENDASI (Data Historis)
        $rekomendasiList = null;
        if ($hasilPrediksi === 'TIDAK LOLOS') {
            $rataRata = collect([
                $request->bind_avg, $request->bing_avg, $request->mtk_wajib_avg, 
                $request->mtk_lanjut_avg, $request->fisika_avg, $request->kimia_avg, 
                $request->biologi_avg
            ])->average();
            
            $rekomendasidata = \App\Models\DatasetPrediksi::where('status', 'LOLOS')
                ->where('rata_keseluruhan', '<=', $rataRata + 1.5)
                ->where('pilihan_1_univ', 'NOT LIKE', '%Indonesia%')
                ->where('pilihan_1_univ', 'NOT LIKE', '%Gadjah Mada%')
                ->where('pilihan_1_univ', 'NOT LIKE', '%UGM%')
                ->where('pilihan_1_univ', 'NOT LIKE', '%UI%')
                ->where('pilihan_1_univ', 'NOT LIKE', '%ITB%')
                ->where('pilihan_1_univ', 'NOT LIKE', '%Teknologi Bandung%')
                ->select('pilihan_1_univ', 'pilihan_1_prodi')
                ->distinct()
                ->limit(20) // Ambil maksimal 20 data unik
                ->get();
                
            if ($rekomendasidata->isNotEmpty()) {
                // Acak koleksi di PHP dan ambil 3 teratas
                $rekomendasiList = json_encode($rekomendasidata->shuffle()->take(3)->values()->toArray());
            }
        }

        // Simpan ke database riwayat_prediksi
        $riwayat = RiwayatPrediksi::create([
            'user_id' => Auth::id(),
            'nama_siswa' => Auth::user()->name,
            'bind_avg' => $request->bind_avg,
            'bing_avg' => $request->bing_avg,
            'mtk_wajib_avg' => $request->mtk_wajib_avg,
            'mtk_lanjut_avg' => $request->mtk_lanjut_avg,
            'fisika_avg' => $request->fisika_avg,
            'kimia_avg' => $request->kimia_avg,
            'biologi_avg' => $request->biologi_avg,
            'univ_pil1' => $request->univ_pil1,
            'prodi_pil1' => $request->prodi_pil1,
            'keketatan_pil1' => $request->keketatan_pil1,
            'univ_pil2' => $request->univ_pil2,
            'prodi_pil2' => $request->prodi_pil2,
            'keketatan_pil2' => $request->keketatan_pil2,
            'prestasi_tertinggi' => $request->prestasi_tertinggi,
            'hasil_prediksi' => $hasilPrediksi,
            'rekomendasi' => $rekomendasiList,
        ]);

        return redirect()->route('siswa.prediksi.hasil', $riwayat->id);
    }

    /**
     * Halaman Hasil Prediksi
     */
    public function hasilPrediksi($id)
    {
        $riwayat = RiwayatPrediksi::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('siswa.prediksi.hasil', compact('riwayat'));
    }

    /**
     * Riwayat Prediksi Siswa (hanya milik user yang login)
     */
    public function riwayat()
    {
        $riwayats = RiwayatPrediksi::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('siswa.riwayat', compact('riwayats'));
    }

    /**
     * Hapus Riwayat Prediksi
     */
    public function destroy($id)
    {
        $riwayat = RiwayatPrediksi::where('user_id', Auth::id())->findOrFail($id);
        $riwayat->delete();

        return back()->with('success', 'Riwayat prediksi berhasil dihapus.');
    }
}
