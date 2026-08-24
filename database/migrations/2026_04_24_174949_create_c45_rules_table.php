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
    Schema::create('c45_rules', function (Blueprint $table) {
        $table->id();
        
        // Menyimpan urutan aturan (misal: "Jika mtk_smt1 > 80 DAN fisika_smt1 > 75")
        $table->text('rule_string'); 
        
        // Hasil akhir dari aturan tersebut
        $table->enum('hasil_prediksi', ['LOLOS', 'TIDAK LOLOS']);
        
        // Opsional: Untuk mencatat kapan model ini dilatih
        $table->integer('batch_training')->default(1); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c45_rules');
    }
};
