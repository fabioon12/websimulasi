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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('thumbnail')->nullable();
            $table->enum('mode', ['mandiri', 'kelompok'])->default('mandiri');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            // Relasi
            $table->foreignId('pengaju_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');    
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
