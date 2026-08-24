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
            
            $existingColumns = Schema::getColumnListing('dataset_prediksi');
            $validColumnsToDrop = array_intersect($columnsToDrop, $existingColumns);
            
            if (!empty($validColumnsToDrop)) {
                $table->dropColumn($validColumnsToDrop);
            }

            // Add new identity & rank
            $table->string('nisn')->nullable()->after('nama_siswa');
            $table->integer('rank_paralel')->nullable()->after('nisn');

            // Add new subjects smt 1-5
            $newMapel1to5 = ['bind', 'bing', 'mtk_wajib'];
            foreach ($newMapel1to5 as $m) {
                for ($i = 1; $i <= 5; $i++) {
                    $table->decimal("{$m}_smt{$i}", 5, 2)->nullable();
                }
            }

            // Add new subjects smt 3-5
            $newMapel3to5 = ['mtk_lanjut', 'fisika', 'kimia', 'biologi'];
            foreach ($newMapel3to5 as $m) {
                for ($i = 3; $i <= 5; $i++) {
                    $table->decimal("{$m}_smt{$i}", 5, 2)->nullable();
                }
            }

            // Add keketatan and prestasi_tertinggi
            $table->string('pil1_keketatan')->nullable()->after('pilihan_1_prodi');
            $table->string('pil2_keketatan')->nullable()->after('pilihan_2_prodi');
            $table->string('prestasi_tertinggi')->nullable()->after('pil2_keketatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dataset_prediksi', function (Blueprint $table) {
            // Simplified down method (not strictly needed for dev, but good practice)
        });
    }
};
