<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Materis extends Model
{
    use HasFactory;
    protected $table = 'materis';

    /**
     * Tentukan tabel jika nama tabelnya bukan 'materis'
     * protected $table = 'materis';
     */

    /**
     * Kolom yang boleh diisi secara massal
     */
    protected $fillable = [
        'judul',
        'user_id',
        'slug',
        'kategori',
        'deskripsi',
        'thumbnail',
        'status',
    ];

    /**
     * Boot function untuk menghandle pembuatan slug otomatis 
     * jika kamu tidak ingin membuatnya manual di Controller.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($materi) {
            if (empty($materi->slug)) {
                $materi->slug = Str::slug($materi->judul);
            }
        });
    }

    /**
     * Aksesor untuk mendapatkan URL Thumbnail yang lengkap
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        // Return placeholder jika gambar tidak ada
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->judul) . '&background=6366f1&color=fff';
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function subMateris()
    {
        return $this->hasMany(SubMateris::class, 'materi_id')->orderBy('urutan', 'asc');
    }
    public function siswas()
    {
        return $this->belongsToMany(User::class, 'materi_siswa', 'materi_id', 'user_id')
                    ->withTimestamps();
    }
    public function progress()
    {
        return $this->hasMany(ProgressSiswa::class, 'materi_id');
    }
}
