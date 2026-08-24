<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('master_universitas')->insert([
        ['id' => 1, 'nama_universitas' => 'Universitas Indonesia', 'singkatan' => 'UI'],
        ['id' => 2, 'nama_universitas' => 'Universitas Gadjah Mada', 'singkatan' => 'UGM'],
        ['id' => 3, 'nama_universitas' => 'Institut Teknologi Bandung', 'singkatan' => 'ITB'],
    ]);
    }
}
