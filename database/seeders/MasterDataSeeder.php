<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterUniversitas;
use App\Models\MasterProdi;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to truncate safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MasterProdi::truncate();
        MasterUniversitas::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data Universitas
        $univ_ui = MasterUniversitas::create([
            'nama_universitas' => 'Universitas Indonesia',
            'singkatan' => 'UI',
        ]);

        $univ_itb = MasterUniversitas::create([
            'nama_universitas' => 'Institut Teknologi Bandung',
            'singkatan' => 'ITB',
        ]);

        $univ_ugm = MasterUniversitas::create([
            'nama_universitas' => 'Universitas Gadjah Mada',
            'singkatan' => 'UGM',
        ]);

        // Data Prodi UI
        $prodi_ui = [
            ['Pendidikan Dokter', 'SAINTEK', 'Tinggi'],
            ['Ilmu Komputer', 'SAINTEK', 'Tinggi'],
            ['Teknik Industri', 'SAINTEK', 'Sedang'],
            ['Teknik Metalurgi dan Material', 'SAINTEK', 'Sedang'],
            ['Ilmu Hukum', 'SOSHUM', 'Sedang'],
            ['Manajemen', 'SOSHUM', 'Tinggi'],
            ['Akuntansi', 'SOSHUM', 'Sedang'],
            ['Ilmu Hubungan Internasional', 'SOSHUM', 'Tinggi'],
            ['Ilmu Komunikasi', 'SOSHUM', 'Tinggi'],
            ['Ilmu Psikologi', 'SOSHUM', 'Tinggi'],
            ['Farmasi', 'SAINTEK', 'Tinggi'],
            ['Arsitektur', 'SAINTEK', 'Tinggi'],
            ['Ilmu Keperawatan', 'SAINTEK', 'Sedang'],
            ['Pendidikan Dokter Gigi', 'SAINTEK', 'Tinggi'],
            ['Teknik Sipil', 'SAINTEK', 'Sedang'],
            ['Ilmu Kesehatan Masyarakat', 'SAINTEK', 'Sedang'],
            ['Aktuaria', 'SAINTEK', 'Tinggi'],
            ['Statistika', 'SAINTEK', 'Tinggi'],
            ['Arkeologi Indonesia', 'SOSHUM', 'Sedang'],
            ['Ilmu Administrasi Negara', 'SOSHUM', 'Sedang'],
            ['Sosiologi', 'SOSHUM', 'Sedang'],
            ['Ilmu Politik', 'SOSHUM', 'Sedang'],
            ['Antropologi Sosial', 'SOSHUM', 'Sedang'],
            ['Fisioterapi', 'SAINTEK', 'Sedang'],
            ['Manajemen Rekod dan Arsip', 'SOSHUM', 'Sedang'],
        ];

        foreach ($prodi_ui as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_ui->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }

        // Data Prodi ITB
        $prodi_itb = [
            ['Fakultas Senirupa dan Desain (FSRD)', 'SOSHUM', 'Sedang'],
            ['Sekolah Farmasi (SF)', 'SAINTEK', 'Sedang'],
            ['Fakultas Ilmu dan Teknologi Kebumian(FITB)', 'SAINTEK', 'Rendah'],
            ['Fakultas Teknik Pertambangan dan Perminyakan (FTTM)', 'SAINTEK', 'Sedang'],
            ['Sekolah Ilmu dan Teknologi Hayati - Sains (SITH-S)', 'SAINTEK', 'Rendah'],
            ['Fakultas Teknik Mesin dan Dirgantara (FTMD)', 'SAINTEK', 'Sedang'],
            ['Sekolah Ilmu dan Teknologi Hayati - Rekayasa (SITH-R)', 'SAINTEK', 'Rendah'],
            ['Fakultas Matematika dan Ilmu Pengetahuan Alam - Matematika (FMIPA-M)', 'SAINTEK', 'Sedang'],
            ['Fakultas Matematika dan Ilmu Pengetahuan Alam - Ipa (FMIPA-I)', 'SAINTEK', 'Rendah'],
            ['Sekolah Teknik Elektro dan Informatika-Komputasi (STEI-K)', 'SAINTEK', 'Tinggi'],
            ['Sekolah Teknik Elektro dan Informatika-Rekayasa (STEI-R)', 'SAINTEK', 'Sedang'],
            ['Sekolah Bisnis dan Manajemen (SBM)', 'SOSHUM', 'Sedang'],
            ['Fakultas Teknik Sipil dan Lingkungan - Infrastruktur Sipil, Lingkungan, dan Kelautan', 'SAINTEK', 'Sedang'],
            ['Fakultas Teknik Sipil dan Lingkungan - Infrastruktur Sumber Daya Air dan Sanitasi Lingkungan', 'SAINTEK', 'Sedang'],
            ['Fakultas Teknologi Industri - Sistem dan Proses (FTI-SP)', 'SAINTEK', 'Sedang'],
        ];

        foreach ($prodi_itb as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_itb->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }

        // Data Prodi UGM
        $prodi_ugm = [
            ['Kedokteran', 'SAINTEK', 'Tinggi'],
            ['Farmasi', 'SAINTEK', 'Tinggi'],
            ['Ilmu Keperawatan', 'SAINTEK', 'Tinggi'],
            ['Gizi', 'SAINTEK', 'Tinggi'],
            ['Kedokteran Gigi', 'SAINTEK', 'Sedang'],
            ['Kedokteran Hewan', 'SAINTEK', 'Rendah'],
            ['Ilmu Komputer', 'SAINTEK', 'Tinggi'],
            ['Statistika', 'SAINTEK', 'Tinggi'],
            ['Agronomi', 'SAINTEK', 'Rendah'],
            ['Proteksi Tanaman (Ilmu Hama dan Penyakit Tumbuhan)', 'SAINTEK', 'Rendah'],
            ['Mikrobiologi Pertanian', 'SAINTEK', 'Rendah'],
            ['Arsitektur', 'SAINTEK', 'Tinggi'],
            ['Teknik Kimia', 'SAINTEK', 'Rendah'],
            ['Teknik Sipil', 'SAINTEK', 'Sedang'],
            ['Teknik Industri', 'SAINTEK', 'Sedang'],
            ['Kehutanan', 'SAINTEK', 'Rendah'],
            ['Ilmu Ekonomi', 'SOSHUM', 'Sedang'],
            ['Manajemen', 'SOSHUM', 'Tinggi'],
            ['Ilmu Hubungan Internasional', 'SOSHUM', 'Tinggi'],
            ['Psikologi', 'SOSHUM', 'Tinggi'],
            ['Ilmu Komunikasi', 'SOSHUM', 'Tinggi'],
            ['Teknik Elektro', 'SAINTEK', 'Sedang'],
            ['Akuntansi', 'SOSHUM', 'Tinggi'],
            ['Sosiologi', 'SOSHUM', 'Sedang'],
            ['Hukum', 'SOSHUM', 'Sedang'],
        ];

        foreach ($prodi_ugm as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_ugm->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }

        // ==========================================
        // DATA TAMBAHAN UNIVERSITAS DARI tambahan data.txt
        // ==========================================

        // 1. Universitas Negeri Jakarta (UNJ)
        $univ_unj = MasterUniversitas::create([
            'nama_universitas' => 'Universitas Negeri Jakarta',
            'singkatan' => 'UNJ',
        ]);

        $prodi_unj = [
            ['Pendidikan Matematika', 'SAINTEK', 'Tinggi'],
            ['Pendidikan Fisika', 'SAINTEK', 'Rendah'],
            ['Pendidikan Kimia', 'SAINTEK', 'Rendah'],
            ['Pendidikan Biologi', 'SAINTEK', 'Sedang'],
            ['Pendidikan Sosiologi', 'SOSHUM', 'Sedang'],
            ['Pendidikan Bisnis', 'SOSHUM', 'Tinggi'],
            ['Pendidikan Teknik Elektro', 'SAINTEK', 'Tinggi'],
            ['Pendidikan Teknik Mesin', 'SAINTEK', 'Rendah'],
            ['Pendidikan Teknik Informatika dan Komputer', 'SAINTEK', 'Tinggi'],
            ['Pendidikan Ekonomi', 'SOSHUM', 'Sedang'],
            ['Ilmu Komputer', 'SAINTEK', 'Tinggi'],
            ['Pendidikan Bahasa Jepang', 'SOSHUM', 'Tinggi'],
            ['Pendidikan Bahasa Perancis', 'SOSHUM', 'Rendah'],
        ];

        foreach ($prodi_unj as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_unj->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }

        // 2. UPN Veteran Jakarta (UPNVJ)
        $univ_upn = MasterUniversitas::create([
            'nama_universitas' => 'UPN Veteran Jakarta',
            'singkatan' => 'UPNVJ',
        ]);

        $prodi_upn = [
            ['Teknik Industri', 'SAINTEK', 'Tinggi'],
            ['Teknik Mesin', 'SAINTEK', 'Sedang'],
            ['Informatika', 'SAINTEK', 'Tinggi'],
            ['Sistem Informasi', 'SAINTEK', 'Tinggi'],
            ['Kedokteran', 'SAINTEK', 'Tinggi'],
            ['Keperawatan', 'SAINTEK', 'Tinggi'],
            ['Manajemen', 'SOSHUM', 'Tinggi'],
            ['Hubungan Internasional', 'SOSHUM', 'Sedang'],
            ['Ilmu Komunikasi', 'SOSHUM', 'Tinggi'],
            ['Ilmu Politik', 'SOSHUM', 'Sedang'],
        ];

        foreach ($prodi_upn as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_upn->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }

        // 3. Institut Pertanian Bogor (IPB)
        $univ_ipb = MasterUniversitas::create([
            'nama_universitas' => 'Institut Pertanian Bogor',
            'singkatan' => 'IPB',
        ]);

        $prodi_ipb = [
            ['Teknologi Pangan', 'SAINTEK', 'Sedang'],
            ['Ilmu Gizi', 'SAINTEK', 'Tinggi'],
            ['Aktuaria', 'SAINTEK', 'Tinggi'],
            ['Akuntansi', 'SOSHUM', 'Sedang'],
            ['Statistika', 'SAINTEK', 'Tinggi'],
            ['Arsitektur', 'SAINTEK', 'Sedang'],
            ['Teknik Sipil & Lingkungan', 'SAINTEK', 'Tinggi'],
            ['Ekonomi Sumberdaya & Lingkungan', 'SOSHUM', 'Sedang'],
            ['Teknologi Industri Pertanian', 'SAINTEK', 'Sedang'],
            ['Manajemen Industri Jasa Makanan & Gizi', 'SAINTEK', 'Sedang'],
        ];

        foreach ($prodi_ipb as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_ipb->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }

        // 4. UIN Syarif Hidayatullah Jakarta (UIN Jakarta)
        $univ_uin = MasterUniversitas::create([
            'nama_universitas' => 'UIN Syarif Hidayatullah Jakarta',
            'singkatan' => 'UIN Jakarta',
        ]);

        $prodi_uin = [
            ['Kesehatan Masyarakat', 'SAINTEK', 'Sedang'],
            ['Pendidikan Matematika', 'SAINTEK', 'Rendah'],
            ['Teknik Informatika', 'SAINTEK', 'Tinggi'],
            ['Ilmu Perpustakaan', 'SOSHUM', 'Rendah'],
            ['Kimia', 'SAINTEK', 'Rendah'],
            ['Farmasi', 'SAINTEK', 'Tinggi'],
            ['Pendidikan Dokter', 'SAINTEK', 'Tinggi'],
            ['Ilmu Hubungan Internasional', 'SOSHUM', 'Sedang'],
            ['Ilmu Hukum', 'SOSHUM', 'Sedang'],
            ['Biologi', 'SAINTEK', 'Rendah'],
        ];

        foreach ($prodi_uin as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_uin->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }

        // 5. Universitas Diponegoro (UNDIP)
        $univ_undip = MasterUniversitas::create([
            'nama_universitas' => 'Universitas Diponegoro',
            'singkatan' => 'UNDIP',
        ]);

        $prodi_undip = [
            ['Kedokteran', 'SAINTEK', 'Tinggi'],
            ['Statistika', 'SAINTEK', 'Sedang'],
            ['Kesehatan Masyarakat', 'SAINTEK', 'Tinggi'],
            ['Teknik Sipil', 'SAINTEK', 'Tinggi'],
            ['Teknik Industri', 'SAINTEK', 'Tinggi'],
            ['Farmasi', 'SAINTEK', 'Tinggi'],
            ['Ilmu Hubungan Internasional', 'SOSHUM', 'Tinggi'],
            ['Ilmu Hukum', 'SOSHUM', 'Sedang'],
            ['Ilmu Komunikasi', 'SOSHUM', 'Tinggi'],
            ['Informatika', 'SAINTEK', 'Tinggi'],
        ];

        foreach ($prodi_undip as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_undip->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }

        // 6. Universitas Airlangga (UNAIR)
        $univ_unair = MasterUniversitas::create([
            'nama_universitas' => 'Universitas Airlangga',
            'singkatan' => 'UNAIR',
        ]);

        $prodi_unair = [
            ['Kedokteran', 'SAINTEK', 'Tinggi'],
            ['Sistem Informasi', 'SAINTEK', 'Tinggi'],
            ['Ilmu Hukum', 'SOSHUM', 'Sedang'],
            ['Ilmu Hubungan Internasional', 'SOSHUM', 'Sedang'],
            ['Akuntansi', 'SOSHUM', 'Sedang'],
            ['Ilmu Komunikasi', 'SOSHUM', 'Tinggi'],
            ['Sastra Indonesia', 'SOSHUM', 'Sedang'],
            ['Sastra Inggris', 'SOSHUM', 'Sedang'],
            ['Sastra Jepang', 'SOSHUM', 'Sedang'],
            ['Antropologi', 'SOSHUM', 'Rendah'],
            ['Ilmu Informasi & Perpustakaan', 'SOSHUM', 'Rendah'],
        ];

        foreach ($prodi_unair as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_unair->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }

        // 7. Universitas Brawijaya (UB)
        $univ_ub = MasterUniversitas::create([
            'nama_universitas' => 'Universitas Brawijaya',
            'singkatan' => 'UB',
        ]);

        $prodi_ub = [
            ['Bioteknologi', 'SAINTEK', 'Sedang'],
            ['Manajemen', 'SOSHUM', 'Tinggi'],
            ['Pendidikan Dokter Gigi', 'SAINTEK', 'Tinggi'],
            ['Teknik Komputer', 'SAINTEK', 'Sedang'],
            ['Teknik Sipil', 'SAINTEK', 'Sedang'],
            ['Sistem Informasi', 'SAINTEK', 'Sedang'],
            ['Ilmu Komunikasi', 'SOSHUM', 'Sedang'],
            ['Sastra Perancis', 'SOSHUM', 'Rendah'],
            ['Sosiologi', 'SOSHUM', 'Rendah'],
            ['Peternakan', 'SAINTEK', 'Rendah'],
        ];

        foreach ($prodi_ub as $p) {
            MasterProdi::create([
                'universitas_id' => $univ_ub->id,
                'nama_prodi' => $p[0],
                'rumpun' => $p[1],
                'keketatan' => ucfirst(strtolower($p[2]))
            ]);
        }
    }
}
