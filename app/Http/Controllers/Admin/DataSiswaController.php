<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;

class DataSiswaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data siswa dengan relasi user
        $query = Siswa::with('user');

        // Filter berdasarkan tahun ajaran jika ada
        if ($request->has('tahun_ajaran') && $request->tahun_ajaran != '') {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        $siswa = $query->paginate(50);
        
        // Ambil daftar tahun ajaran yang unik untuk dropdown filter
        $tahunAjaranList = Siswa::select('tahun_ajaran')
                                ->distinct()
                                ->whereNotNull('tahun_ajaran')
                                ->orderBy('tahun_ajaran', 'desc')
                                ->pluck('tahun_ajaran');

        return view('admin.siswa.index', compact('siswa', 'tahunAjaranList'));
    }

    public function import(Request $request)
    {
        // Hindari error Maximum Execution Time (karena proses enkripsi password untuk ratusan siswa butuh waktu)
        set_time_limit(0);
        
        // Matikan pencatatan log query sementara agar memori RAM tidak penuh (Error Allowed Memory Size)
        DB::disableQueryLog();

        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
            'import_tahun_ajaran' => 'required'
        ]);

        try {
            $file = $request->file('file_excel');
            $tahunAjaran = $request->input('import_tahun_ajaran');
            
            // Tambahkan getClientOriginalExtension() agar SimpleExcel tahu format aslinya
            $ext = $file->getClientOriginalExtension();
            SimpleExcelReader::create($file->getRealPath(), $ext)
                ->getRows()
                ->each(function (array $originalRow) use ($tahunAjaran) {
                    
                    // Membuat header kolom otomatis menjadi huruf kecil dan MENGUBAH SPASI JADI UNDERSCORE
                    // Supaya jika Guru BK mengetik "Nama Lengkap", otomatis terbaca sebagai "nama_lengkap"
                    $row = [];
                    foreach ($originalRow as $key => $value) {
                        $cleanKey = str_replace(' ', '_', strtolower(trim($key)));
                        $row[$cleanKey] = $value;
                    }

                    // Kita butuh minimal NISN untuk membuat akun
                    if (!isset($row['nisn']) || empty(trim($row['nisn']))) {
                        return; // Lewati baris ini jika NISN kosong
                    }

                    $nisn = trim($row['nisn']);
                    $namaLengkap = $row['nama_lengkap'] ?? $row['nama'] ?? '-';
                    $jenisKelamin = $row['jenis_kelamin'] ?? $row['jk'] ?? 'L';
                    $kelas = $row['kelas'] ?? null;

                    DB::transaction(function () use ($nisn, $namaLengkap, $jenisKelamin, $kelas, $tahunAjaran) {
                        
                        // 1. Buat atau update data User
                        $user = User::updateOrCreate(
                            ['username' => $nisn],
                            [
                                'name' => $namaLengkap,
                                'password' => Hash::make($nisn), // Password default adalah NISN
                                'role' => 'siswa'
                            ]
                        );

                        // 2. Buat atau update profil Siswa
                        Siswa::updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'nisn' => $nisn,
                                'nama_lengkap' => $namaLengkap,
                                'asal_sekolah' => '-', // Tidak wajib diisi karena khusus 1 sekolah
                                'jenis_kelamin' => $jenisKelamin,
                                'kelas' => $kelas,
                                'tahun_ajaran' => $tahunAjaran, // Mengambil dari dropdown form
                            ]
                        );
                    });
                });

            return redirect()->back()->with('success', 'Data Siswa berhasil di-import dan akun telah digenerate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal meng-import data: Cek format kolom di Excel kamu. Error: ' . $e->getMessage());
        }
    }
}
