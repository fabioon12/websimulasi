<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['proposal_id', 'milestone_id', 'user_id', 'nama_tugas', 'deskripsi', 'prioritas', 'status'];
}
