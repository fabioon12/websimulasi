<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proyeks extends Model
{
    use HasFactory;

    // Tambahkan baris ini
    protected $fillable = [
        'nama_proyek',
        'kelas',
        'deadline',
        'deskripsi',
        'cover',
        'mode',
        'kesulitan',
        'max_siswa',
        'guru_id',
    ];

    /**
     * Relasi ke Roles (Satu proyek punya banyak roles)
     */
    public function roles()
    {
        return $this->hasMany(Proyek_roles::class, 'proyek_id');
    }
    public function guru()
    {
        // Parameter kedua 'guru_id' adalah nama kolom foreign key di tabel proyeks
        return $this->belongsTo(User::class, 'guru_id');
    }
}
