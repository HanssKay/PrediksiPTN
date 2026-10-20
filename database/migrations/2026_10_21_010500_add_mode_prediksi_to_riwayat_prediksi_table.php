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
            if (!Schema::hasColumn('riwayat_prediksi', 'mode_prediksi')) {
                $table->string('mode_prediksi')->default('resmi')->after('hasil_prediksi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_prediksi', function (Blueprint $table) {
            if (Schema::hasColumn('riwayat_prediksi', 'mode_prediksi')) {
                $table->dropColumn('mode_prediksi');
            }
        });
    }
};
