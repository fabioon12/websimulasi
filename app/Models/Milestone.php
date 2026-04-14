<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'nama_milestone',
        'deadline',
        'is_completed',
        'feedback_guru',
        'status_review',
        'reviewed_at'
    ];

    // Format tanggal untuk casting agar lebih mudah diolah Carbon
    protected $casts = [
        'deadline' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }
    public function logbooks() 
    {
        return $this->hasMany(Logbook::class)->latest('tanggal_kerjakan');
    }
    /**
     * Helper: Mengecek apakah milestone ini sudah melewati deadline
     */
    public function isOverdue()
    {
        return $this->deadline->isPast() && !$this->is_completed;
    }
}
