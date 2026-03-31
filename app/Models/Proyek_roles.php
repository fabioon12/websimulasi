<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyek_roles extends Model
{
    protected $fillable = [
        'proyek_id',
        'nama_role',
    ];
    public function roadmaps()
    {
        return $this->hasMany(Roadmap::class, 'proyek_role_id');
    }
}
