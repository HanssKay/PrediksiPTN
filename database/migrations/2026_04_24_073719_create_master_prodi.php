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
        Schema::create('master_prodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('universitas_id')->constrained('master_universitas')->onDelete('cascade');
            $table->string('nama_prodi');
            $table->enum('rumpun', ['SAINTEK', 'SOSHUM']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_prodi');
    }
};
