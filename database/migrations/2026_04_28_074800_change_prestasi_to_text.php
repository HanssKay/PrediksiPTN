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
            $table->text('prestasi_1')->nullable()->change();
            $table->text('prestasi_2')->nullable()->change();
            $table->text('prestasi_3')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dataset_prediksi', function (Blueprint $table) {
            $table->string('prestasi_1')->nullable()->change();
            $table->string('prestasi_2')->nullable()->change();
            $table->string('prestasi_3')->nullable()->change();
        });
    }
};
