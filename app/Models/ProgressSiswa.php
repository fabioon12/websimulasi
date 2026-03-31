<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgressSiswa extends Model
{
    use HasFactory;

    // Nama tabel di database (opsional jika nama file sudah jamak)
    protected $table = 'progress_siswas';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'materi_id',
        'sub_materi_id',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Materi Utama
     */
    public function materi()
    {
        return $this->belongsTo(Materis::class, 'materi_id');
    }

    /**
     * Relasi ke Sub Materi
     */
    public function subMateri()
    {
        return $this->belongsTo(SubMateris::class, 'sub_materi_id');
    }
}
