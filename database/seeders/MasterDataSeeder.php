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
    }
}
