<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyekSiswa extends Model
{
    use HasFactory;

    protected $table = 'proyek_siswa';

    protected $fillable = [
        'user_id',
        'proyek_id',
        'proyek_role_id',
        'progress',
        'status',
        'joined_at'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyeks::class);
    }
    public function role(): BelongsTo
    {
        return $this->belongsTo(Proyek_roles::class, 'proyek_role_id');
    }
}
