<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roadmap extends Model
{
    protected $fillable = ['proyek_role_id', 'judul_tugas', 'instruksi', 'urutan', 'deadline_tugas', 
    'poin'];

    public function Proyek_roles()
    {
        return $this->belongsTo(Proyek_roles::class, 'proyek_role_id');
    }
}
