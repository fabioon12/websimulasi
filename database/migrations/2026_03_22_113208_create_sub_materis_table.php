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
        Schema::create('sub_materis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materis')->onDelete('cascade');
            $table->string('judul');
            $table->string('kategori');
            $table->integer('urutan');
            $table->text('bacaan')->nullable();
            $table->string('video_url')->nullable(); 
            $table->string('pdf_path')->nullable(); 
            $table->text('instruksi_coding')->nullable();
            $table->text('starter_code')->nullable();
            $table->json('kuis_data')->nullable();
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_materis');
    }
};
