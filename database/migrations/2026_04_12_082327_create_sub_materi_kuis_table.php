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
        Schema::create('sub_materi_kuis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sub_materi_id')->constrained('sub_materis')->onDelete('cascade');
        $table->text('pertanyaan');
        $table->string('gambar_pertanyaan')->nullable(); 
        $table->integer('point')->default(10);

        // Opsi Teks
        $table->string('opsi_a');
        $table->string('opsi_b');
        $table->string('opsi_c');
        $table->string('opsi_d');

        // TAMBAHKAN INI: Kolom untuk gambar di setiap opsi
        $table->string('opsi_a_img')->nullable();
        $table->string('opsi_b_img')->nullable();
        $table->string('opsi_c_img')->nullable();
        $table->string('opsi_d_img')->nullable();

        $table->char('jawaban', 1); 
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_materi_kuis');
    }
};
