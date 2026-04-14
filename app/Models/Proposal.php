<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'thumbnail', 'mode', 'tanggal_mulai', 
    'tanggal_selesai','status', 'pengaju_id', 'guru_id'];
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
    public function anggota()
    {
        return $this->hasMany(ProposalMember::class, 'proposal_id');
    }
    public function milestones()
    {
        return $this->hasMany(Milestone::class)->orderBy('deadline', 'asc');
    }

    // Menghitung progress otomatis berdasarkan milestone yang selesai
    public function getProgressAttribute()
    {
        $total = $this->milestones()->count();
        if ($total == 0) return 0;
        $completed = $this->milestones()->where('is_completed', true)->count();
        return round(($completed / $total) * 100);
    }
    public function tasks() {
        return $this->hasMany(Task::class);
    }
    public function logbooks()
    {
        return $this->hasMany(Logbook::class)->latest();
    }
}
