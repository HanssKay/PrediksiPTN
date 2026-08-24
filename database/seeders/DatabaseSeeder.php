<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
        'name' => 'Administrator',
        'username' => 'admin',
        'password' => Hash::make('passwordadmin'), // Password admin
        'role' => 'admin', // Sesuai kolom role kamu
    ]);

    // 2. Buat Akun Siswa (Skenario Opsi 2)
    // Anggap tanggal lahir siswa adalah 17 Agustus 2005 (17082005)
    User::create([
        'name' => 'Budi Santoso',
        'username' => '0012345678', // Ini NISN sebagai username
        'password' => Hash::make('17082005'), // Ini Tanggal Lahir sebagai password
        'role' => 'siswa',
    ]);

        $this->call([
            MasterDataSeeder::class,
        ]);
    }
}
