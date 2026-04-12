<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubMateriKuis extends Model
{
    protected $table = 'sub_materi_kuis';
    protected $fillable = [
        'sub_materi_id', 
        'pertanyaan', 
        'gambar_pertanyaan', // Tambahkan ini
        'point',             // Pastikan nama kolom di DB adalah 'point' atau 'skor'
        'opsi_a', 
        'opsi_a_img',        // Tambahkan ini
        'opsi_b', 
        'opsi_b_img',        // Tambahkan ini
        'opsi_c', 
        'opsi_c_img',        // Tambahkan ini
        'opsi_d', 
        'opsi_d_img',        // Tambahkan ini
        'jawaban'
    ];
}
