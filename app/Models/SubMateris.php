<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubMateris extends Model
{
    use HasFactory;

    protected $table = 'sub_materis';

    protected $fillable = [
        'materi_id',
        'judul',
        'tipe',
        'kategori',
        'urutan',
        'bacaan',
        'video_url',
        'pdf_path',
        'instruksi_coding',
        'starter_code',
        'kuis_data',
    ];
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materis::class, 'materi_id');
    }
    public function kuis()
    {
        return $this->hasMany(SubMateriKuis::class, 'sub_materi_id');
    }
}
