<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_prediksi', function (Blueprint $table) {
            $table->id();
            
            // Data Identitas Siswa (bisa null jika tamu/guest)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_siswa')->nullable(); // Disimpan terpisah buat jaga-jaga kalau user dihapus
            
            // Rata-rata 5 Semester
            $table->decimal('bind_avg', 5, 2)->nullable();
            $table->decimal('bing_avg', 5, 2)->nullable();
            $table->decimal('mtk_wajib_avg', 5, 2)->nullable();
            $table->decimal('mtk_lanjut_avg', 5, 2)->nullable();
            $table->decimal('fisika_avg', 5, 2)->nullable();
            $table->decimal('kimia_avg', 5, 2)->nullable();
            $table->decimal('biologi_avg', 5, 2)->nullable();
            
            // Data Jurusan & Keketatan
            $table->string('univ_pil1')->nullable();
            $table->string('prodi_pil1')->nullable();
            $table->string('keketatan_pil1')->nullable();
            
            $table->string('univ_pil2')->nullable();
            $table->string('prodi_pil2')->nullable();
            $table->string('keketatan_pil2')->nullable();
            
            // Prestasi
            $table->string('prestasi_tertinggi')->nullable();
            
            // Hasil Prediksi (Output C4.5)
            $table->string('hasil_prediksi')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_prediksi');
    }
};
