<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengumpulanTugas extends Model
{
    use HasFactory;
    protected $table = 'pengumpulan_tugas';
    protected $fillable = [
        'user_id',
        'proyek_id',
        'roadmap_id',
        'link_repo',
        'catatan_siswa',
        'feedback_guru',
        'poin_didapat',
        'status', 
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyeks::class, 'proyek_id');
    }
    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class, 'roadmap_id');
    }
}
