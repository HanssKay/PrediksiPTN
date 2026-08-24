<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            // UNIVERSITAS INDONESIA (ID: 1)
            ['id' => 1, 'universitas_id' => 1, 'nama_prodi' => 'Pendidikan Dokter', 'rumpun' => 'SAINTEK'],
            ['id' => 2, 'universitas_id' => 1, 'nama_prodi' => 'Ilmu Komputer', 'rumpun' => 'SAINTEK'],
            ['id' => 3, 'universitas_id' => 1, 'nama_prodi' => 'Sistem Informasi', 'rumpun' => 'SAINTEK'],
            ['id' => 4, 'universitas_id' => 1, 'nama_prodi' => 'Teknik Industri', 'rumpun' => 'SAINTEK'],
            ['id' => 5, 'universitas_id' => 1, 'nama_prodi' => 'Teknik Metalurgi dan Material', 'rumpun' => 'SAINTEK'],
            ['id' => 6, 'universitas_id' => 1, 'nama_prodi' => 'Ilmu Hukum', 'rumpun' => 'SOSHUM'],
            ['id' => 7, 'universitas_id' => 1, 'nama_prodi' => 'Manajemen', 'rumpun' => 'SOSHUM'],
            ['id' => 8, 'universitas_id' => 1, 'nama_prodi' => 'Akuntansi', 'rumpun' => 'SOSHUM'],
            ['id' => 9, 'universitas_id' => 1, 'nama_prodi' => 'Ilmu Hubungan Internasional', 'rumpun' => 'SOSHUM'],
            ['id' => 10, 'universitas_id' => 1, 'nama_prodi' => 'Ilmu Komunikasi', 'rumpun' => 'SOSHUM'],
            ['id' => 11, 'universitas_id' => 1, 'nama_prodi' => 'Psikologi', 'rumpun' => 'SOSHUM'],

            // UNIVERSITAS GADJAH MADA (ID: 2)
            ['id' => 12, 'universitas_id' => 2, 'nama_prodi' => 'Kedokteran', 'rumpun' => 'SAINTEK'],
            ['id' => 13, 'universitas_id' => 2, 'nama_prodi' => 'Farmasi', 'rumpun' => 'SAINTEK'],
            ['id' => 14, 'universitas_id' => 2, 'nama_prodi' => 'Teknologi Informasi', 'rumpun' => 'SAINTEK'],
            ['id' => 15, 'universitas_id' => 2, 'nama_prodi' => 'Teknik Geologi', 'rumpun' => 'SAINTEK'],
            ['id' => 16, 'universitas_id' => 2, 'nama_prodi' => 'Kehutanan', 'rumpun' => 'SAINTEK'],
            ['id' => 17, 'universitas_id' => 2, 'nama_prodi' => 'Ilmu Hukum', 'rumpun' => 'SOSHUM'],
            ['id' => 18, 'universitas_id' => 2, 'nama_prodi' => 'Manajemen', 'rumpun' => 'SOSHUM'],
            ['id' => 19, 'universitas_id' => 2, 'nama_prodi' => 'Psikologi', 'rumpun' => 'SOSHUM'],
            ['id' => 20, 'universitas_id' => 2, 'nama_prodi' => 'Ilmu Hubungan Internasional', 'rumpun' => 'SOSHUM'],
            ['id' => 21, 'universitas_id' => 2, 'nama_prodi' => 'Sosiologi', 'rumpun' => 'SOSHUM'],

            // INSTITUT TEKNOLOGI BANDUNG (ID: 3)
            ['id' => 22, 'universitas_id' => 3, 'nama_prodi' => 'Sekolah Teknik Elektro dan Informatika (STEI) - Komputasi', 'rumpun' => 'SAINTEK'],
            ['id' => 23, 'universitas_id' => 3, 'nama_prodi' => 'Sekolah Teknik Elektro dan Informatika (STEI) - Rekayasa', 'rumpun' => 'SAINTEK'],
            ['id' => 24, 'universitas_id' => 3, 'nama_prodi' => 'Fakultas Teknik Pertambangan dan Perminyakan (FTTM)', 'rumpun' => 'SAINTEK'],
            ['id' => 25, 'universitas_id' => 3, 'nama_prodi' => 'Fakultas Teknik Mesin dan Dirgantara (FTMD)', 'rumpun' => 'SAINTEK'],
            ['id' => 26, 'universitas_id' => 3, 'nama_prodi' => 'Sekolah Ilmu dan Teknologi Hayati (SITH) - Sains', 'rumpun' => 'SAINTEK'],
            ['id' => 27, 'universitas_id' => 3, 'nama_prodi' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam (FMIPA)', 'rumpun' => 'SAINTEK'],
            ['id' => 28, 'universitas_id' => 3, 'nama_prodi' => 'Sekolah Bisnis dan Manajemen (SBM)', 'rumpun' => 'SOSHUM'],
            ['id' => 29, 'universitas_id' => 3, 'nama_prodi' => 'Fakultas Seni Rupa dan Desain (FSRD)', 'rumpun' => 'SOSHUM'],
            ['id' => 30, 'universitas_id' => 3, 'nama_prodi' => 'Kriya (FSRD)', 'rumpun' => 'SOSHUM'],
            ['id' => 31, 'universitas_id' => 3, 'nama_prodi' => 'Desain Komunikasi Visual (FSRD)', 'rumpun' => 'SOSHUM'],
        ];

        foreach ($prodis as $prodi) {
            DB::table('master_prodi')->updateOrInsert(
                ['id' => $prodi['id']], // Cari berdasarkan ID
                [
                    'universitas_id' => $prodi['universitas_id'],
                    'nama_prodi' => $prodi['nama_prodi'],
                    'rumpun' => $prodi['rumpun'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}