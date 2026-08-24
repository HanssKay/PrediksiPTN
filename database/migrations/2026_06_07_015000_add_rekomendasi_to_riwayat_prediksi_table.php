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
        Schema::table('riwayat_prediksi', function (Blueprint $table) {
            $table->json('rekomendasi')->nullable()->after('hasil_prediksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_prediksi', function (Blueprint $table) {
            $table->dropColumn('rekomendasi');
        });
    }
};
