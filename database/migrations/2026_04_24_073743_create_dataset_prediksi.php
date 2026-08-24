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
        Schema::create('dataset_prediksi', function (Blueprint $table) {
            $table->id();
            
            // Daftar semua mata pelajaran dari file SQL kamu
            $mapel = [
                'ppkn', 'prancis', 'mat_lanjut', 'fisika', 'pkwu', 'inf', 
                'pjok', 'indo', 'inggris', 'biologi', 'ekonomi', 'geografi', 
                'kimia', 'mat_minat', 'agama', 'sejarah', 'senbud', 'sosiologi', 'rata'
            ];

            // Looping otomatis untuk membuat kolom smt1 sampai smt5
            foreach ($mapel as $m) {
                for ($i = 1; $i <= 5; $i++) {
                    $table->decimal("{$m}_smt{$i}", 5, 2)->nullable();
                }
            }

            $table->decimal('rata_keseluruhan', 5, 2);
            $table->string('pilihan_1_univ');
            $table->string('pilihan_1_prodi');
            $table->string('pilihan_2_univ')->nullable();
            $table->string('pilihan_2_prodi')->nullable();
            $table->string('prestasi_1')->nullable();
            $table->string('prestasi_2')->nullable();
            $table->string('prestasi_3')->nullable();
            $table->enum('status', ['LOLOS', 'TIDAK LOLOS']);
            
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataset_prediksi');
    }
};
