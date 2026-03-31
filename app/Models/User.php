<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'point',
        'identity_number',
        'class',
        'major',           
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function daftarMateri()
    {
        return $this->belongsToMany(Materis::class, 'materi_siswa', 'user_id', 'materi_id')
                    ->withTimestamps();
    }

    /**
     * Helper untuk cek apakah siswa sudah mengambil materi tertentu
     */
    public function sudahAmbilMateri($materiId)
    {
        return $this->daftarMateri()->where('materi_id', $materiId)->exists();
    }
    public function progress()
    {
        return $this->hasMany(ProgressSiswa::class);
    }
    public function proyeks()
    {
        return $this->hasMany(Proyeks::class, 'guru_id');
    }
    public function proyekSiswa()
    {
        return $this->hasMany(ProyekSiswa::class, 'user_id');
    }
    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
    public function pengumpulanTugas()
    {
 
        return $this->hasMany(PengumpulanTugas::class, 'user_id');
    }

    /**
     * Mengambil inisial nama untuk avatar default di leaderboard
     * Contoh: "Budi Santoso" -> "BS"
     */
    public function getInisialAttribute()
    {
        return collect(explode(' ', $this->name))
            ->map(fn($segment) => mb_substr($segment, 0, 1))
            ->join('');
    }
}
