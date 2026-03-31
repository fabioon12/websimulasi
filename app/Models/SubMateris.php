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
        'kategori',
        'urutan',
        'bacaan',
        'video_url',
        'pdf_path',
        'instruksi_coding',
        'starter_code',
    ];
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materis::class, 'materi_id');
    }
}
