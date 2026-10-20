<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\Admin\DatasetController;
use App\Http\Controllers\Admin\C45Controller;
use Illuminate\Support\Facades\Route;

// Rute default dari Breeze
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute logout manual untuk mengatasi stuck error
Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});

// ROUTE SEMENTARA: Update/Seed Master Universitas & Prodi ke Database Hostinger
Route::get('/seed-universitas', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'MasterDataSeeder',
            '--force' => true,
        ]);
        $totalUniv = \App\Models\MasterUniversitas::count();
        $totalProdi = \App\Models\MasterProdi::count();
        return "BERHASIL! Database & Migrasi berhasil diperbarui:<br>- Total Universitas: <b>{$totalUniv} PTN</b><br>- Total Program Studi: <b>{$totalProdi} Prodi</b><br><br><a href='/siswa/prediksi'>Klik di sini untuk buka Form Siswa</a>";
    } catch (\Exception $e) {
        return "GAGAL SEEDING: " . $e->getMessage();
    }
});

// ROUTE DARURAT UNTUK MEMPERBAIKI DATABASE
Route::get('/fix-db', function () {
    try {
        \Illuminate\Support\Facades\Schema::table('dataset_prediksi', function (\Illuminate\Database\Schema\Blueprint $table) {
            $existingColumns = \Illuminate\Support\Facades\Schema::getColumnListing('dataset_prediksi');
            
            // Drop old columns
            $oldMapel = [
                'ppkn', 'prancis', 'mat_lanjut', 'fisika', 'pkwu', 'inf', 
                'pjok', 'indo', 'inggris', 'biologi', 'ekonomi', 'geografi', 
                'kimia', 'mat_minat', 'agama', 'sejarah', 'senbud', 'sosiologi', 'rata'
            ];
            $columnsToDrop = [];
            foreach ($oldMapel as $m) {
                for ($i = 1; $i <= 5; $i++) {
                    $columnsToDrop[] = "{$m}_smt{$i}";
                }
            }
            $columnsToDrop = array_merge($columnsToDrop, ['prestasi_1', 'prestasi_2', 'prestasi_3']);
            $validColumnsToDrop = array_intersect($columnsToDrop, $existingColumns);
            if (!empty($validColumnsToDrop)) {
                $table->dropColumn($validColumnsToDrop);
            }

            // Add new identity & rank
            if (!in_array('nisn', $existingColumns)) $table->string('nisn')->nullable();
            if (!in_array('rank_paralel', $existingColumns)) $table->integer('rank_paralel')->nullable();

            // Mapel 1-5
            $newMapel1to5 = ['bind', 'bing', 'mtk_wajib'];
            foreach ($newMapel1to5 as $m) {
                for ($i = 1; $i <= 5; $i++) {
                    $colName = "{$m}_smt{$i}";
                    if (!in_array($colName, $existingColumns)) $table->decimal($colName, 5, 2)->nullable();
                }
            }

            // Mapel 3-5
            $newMapel3to5 = ['mtk_lanjut', 'fisika', 'kimia', 'biologi'];
            foreach ($newMapel3to5 as $m) {
                for ($i = 3; $i <= 5; $i++) {
                    $colName = "{$m}_smt{$i}";
                    if (!in_array($colName, $existingColumns)) $table->decimal($colName, 5, 2)->nullable();
                }
            }

            // Keketatan
            if (!in_array('pil1_keketatan', $existingColumns)) $table->string('pil1_keketatan')->nullable();
            if (!in_array('pil2_keketatan', $existingColumns)) $table->string('pil2_keketatan')->nullable();
            if (!in_array('prestasi_tertinggi', $existingColumns)) $table->string('prestasi_tertinggi')->nullable();
        });
        return "DATABASE BERHASIL DIPERBAIKI! Silakan kembali ke web dan coba import lagi.";
    } catch (\Exception $e) {
        return "GAGAL: " . $e->getMessage();
    }
});

// ROUTE SEMENTARA: Buat akun siswa untuk testing
Route::get('/buat-akun-siswa', function () {
    try {
        $exists = \Illuminate\Support\Facades\DB::table('users')->where('email', 'siswa@test.com')->first();
        if ($exists) {
            \Illuminate\Support\Facades\DB::table('users')->where('email', 'siswa@test.com')->update([
                'password' => bcrypt('password'),
                'role' => 'siswa',
            ]);
        } else {
            \Illuminate\Support\Facades\DB::table('users')->insert([
                'name' => 'Siswa Demo',
                'username' => 'siswademo',
                'email' => 'siswa@test.com',
                'password' => bcrypt('password'),
                'role' => 'siswa',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return "AKUN SISWA BERHASIL DIBUAT!<br><br>Email: <b>siswa@test.com</b><br>Password: <b>password</b><br><br><a href='/login'>Klik di sini untuk Login</a>";
    } catch (\Exception $e) {
        return "GAGAL: " . $e->getMessage();
    }
});

// Middleware auth memastikan user harus login
Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kelompok Rute untuk Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        // Riwayat Prediksi
        Route::get('/riwayat', [\App\Http\Controllers\Admin\RiwayatController::class, 'index'])->name('riwayat.index');
        Route::delete('/riwayat/{id}', [\App\Http\Controllers\Admin\RiwayatController::class, 'destroy'])->name('riwayat.destroy');

        // Legacy dataset route (redirect ke c45.dataset)
        Route::get('/dataset', [DatasetController::class, 'index'])->name('dataset.index');
        Route::post('/dataset/import', [DatasetController::class, 'import'])->name('dataset.import');

        // Perhitungan C4.5 - Sub Menu
        Route::prefix('c45')->name('c45.')->group(function () {
            // Input Dataset
            Route::get('/dataset', [C45Controller::class, 'dataset'])->name('dataset');
            Route::post('/dataset/import', [C45Controller::class, 'importDataset'])->name('dataset.import');
            Route::delete('/dataset/{id}', [C45Controller::class, 'destroyDataset'])->name('dataset.destroy');
            Route::delete('/dataset-all', [C45Controller::class, 'destroyAllDataset'])->name('dataset.destroyAll');

            // Preprocessing
            Route::get('/preprocessing', [C45Controller::class, 'preprocessing'])->name('preprocessing');
            Route::post('/preprocessing/refresh', [C45Controller::class, 'refreshPreprocessing'])->name('preprocessing.refresh');

            // Proses Perhitungan
            Route::get('/perhitungan', [C45Controller::class, 'perhitungan'])->name('perhitungan');
            Route::post('/perhitungan/process', [C45Controller::class, 'process'])->name('process');
            Route::get('/perhitungan/download-oversampled', [C45Controller::class, 'downloadOversampled'])->name('perhitungan.downloadOversampled');

            // Pohon Keputusan
            Route::get('/pohon-keputusan', [C45Controller::class, 'pohonKeputusan'])->name('pohon');

            // Pengujian Akurasi
            Route::get('/pengujian', [C45Controller::class, 'pengujian'])->name('pengujian');
            Route::post('/pengujian/run', [C45Controller::class, 'runTest'])->name('pengujian.run');
        });

        // Manajemen Siswa (Import)
        Route::get('/siswa', [\App\Http\Controllers\Admin\DataSiswaController::class, 'index'])->name('siswa.index');
        Route::post('/siswa/import', [\App\Http\Controllers\Admin\DataSiswaController::class, 'import'])->name('siswa.import');
    });

    // Kelompok Rute untuk Siswa
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaController::class, 'index'])->name('dashboard');
        
        // Prediksi
        Route::get('/prediksi', [SiswaController::class, 'formPrediksi'])->name('prediksi.form');
        Route::post('/prediksi', [SiswaController::class, 'prosesPrediski'])->name('prediksi.proses');
        Route::get('/prediksi/hasil/{id}', [SiswaController::class, 'hasilPrediksi'])->name('prediksi.hasil');
        
        // Riwayat
        Route::get('/riwayat', [SiswaController::class, 'riwayat'])->name('riwayat');
        Route::delete('/prediksi/{id}', [SiswaController::class, 'destroy'])->name('prediksi.destroy');
    });

});

require __DIR__.'/auth.php';