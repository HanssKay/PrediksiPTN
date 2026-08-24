<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dataset_prediksi', function (Blueprint $table) {
            // Drop old subjects (1-5)
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
            
            // HANYA hapus kolom yang benar-benar masih ada di tabel
            $existingColumns = Schema::getColumnListing('dataset_prediksi');
            $validColumnsToDrop = array_intersect($columnsToDrop, $existingColumns);
            
            if (!empty($validColumnsToDrop)) {
                $table->dropColumn($validColumnsToDrop);
            }

            // --- TAMBAH KOLOM BARU ---
            // Cek dulu apakah kolomnya sudah ada (mencegah error kalau sempat terbuat)
            if (!in_array('nisn', $existingColumns)) {
                $table->string('nisn')->nullable()->after('nama_siswa');
            }
            if (!in_array('rank_paralel', $existingColumns)) {
                $table->integer('rank_paralel')->nullable()->after('nama_siswa'); // letakkan setelah nama
            }

            // Mapel 1-5 baru
            $newMapel1to5 = ['bind', 'bing', 'mtk_wajib'];
            foreach ($newMapel1to5 as $m) {
                for ($i = 1; $i <= 5; $i++) {
                    $colName = "{$m}_smt{$i}";
                    if (!in_array($colName, $existingColumns)) {
                        $table->decimal($colName, 5, 2)->nullable();
                    }
                }
            }

            // Mapel 3-5 baru
            $newMapel3to5 = ['mtk_lanjut', 'fisika', 'kimia', 'biologi'];
            foreach ($newMapel3to5 as $m) {
                for ($i = 3; $i <= 5; $i++) {
                    $colName = "{$m}_smt{$i}";
                    if (!in_array($colName, $existingColumns)) {
                        $table->decimal($colName, 5, 2)->nullable();
                    }
                }
            }

            // Keketatan dan prestasi
            if (!in_array('pil1_keketatan', $existingColumns)) {
                $table->string('pil1_keketatan')->nullable()->after('pilihan_1_prodi');
            }
            if (!in_array('pil2_keketatan', $existingColumns)) {
                $table->string('pil2_keketatan')->nullable()->after('pilihan_2_prodi');
            }
            if (!in_array('prestasi_tertinggi', $existingColumns)) {
                $table->string('prestasi_tertinggi')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
