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
        Schema::create('proyek_siswa', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('proyek_id')->constrained('proyeks')->onDelete('cascade');
        $table->foreignId('proyek_role_id')->constrained('proyek_roles')->onDelete('cascade');
        $table->integer('progress')->default(0);
        $table->string('status')->default('active'); 
        $table->timestamp('joined_at')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek_siswa');
    }
};
