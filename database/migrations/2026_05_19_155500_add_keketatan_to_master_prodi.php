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
        Schema::table('master_prodi', function (Blueprint $table) {
            $table->enum('keketatan', ['Rendah', 'Sedang', 'Tinggi'])->nullable()->after('rumpun');
            // Since we need to modify rumpun to be nullable, we can try dropping it and recreating or using change().
            // Let's just use change() for rumpun.
            $table->enum('rumpun', ['SAINTEK', 'SOSHUM'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_prodi', function (Blueprint $table) {
            $table->dropColumn('keketatan');
            $table->enum('rumpun', ['SAINTEK', 'SOSHUM'])->nullable(false)->change();
        });
    }
};
