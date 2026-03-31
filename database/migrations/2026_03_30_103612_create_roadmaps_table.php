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
        Schema::create('roadmaps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('proyek_role_id')->constrained('proyek_roles')->onDelete('cascade');
        $table->string('judul_tugas');
        $table->text('instruksi');
        $table->integer('urutan')->default(1);
        $table->date('deadline_tugas')->nullable(); 
        $table->integer('poin')->default(0);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmaps');
    }
};
