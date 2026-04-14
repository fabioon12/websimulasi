<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalMember extends Model
{
    protected $fillable = ['proposal_id', 'user_id', 'role', 'is_leader', 'status_konfirmasi'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
