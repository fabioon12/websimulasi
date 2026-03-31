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
        Schema::create('proyeks', function (Blueprint $table) {
        $table->id();
        $table->string('nama_proyek');
        $table->string('kelas');
        $table->date('deadline');
        $table->text('deskripsi')->nullable();
        $table->string('cover')->nullable();
        $table->enum('mode', ['individu', 'kelompok'])->default('kelompok');
        $table->integer('max_siswa')->default(1);
        $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyeks');
    }
};
