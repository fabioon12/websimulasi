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
        Schema::create('pengumpulan_tugas', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('proyek_id')->constrained('proyeks')->onDelete('cascade');
            
            // Relasi ke roadmap (tugas spesifik apa yang dikumpulkan)
            $table->foreignId('roadmap_id')->constrained('roadmaps')->onDelete('cascade');
            
            $table->text('link_repo')->nullable(); // Link GitHub / Drive
            $table->text('catatan_siswa')->nullable();
            $table->text('feedback_guru')->nullable();
            
            $table->integer('poin_didapat')->default(0);
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
