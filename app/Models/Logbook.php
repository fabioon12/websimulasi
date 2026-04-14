<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'milestone_id',
        'user_id',
        'judul',
        'deskripsi',
        'tanggal_kerjakan',
        'lampiran',
        'file_type',
        'feedback_guru',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }
    /**
     * Mendapatkan proposal/proyek terkait.
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
}
