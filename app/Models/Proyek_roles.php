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
    public function users()
    {
        return $this->hasMany(User::class, 'proyek_role_id');
    }
    public function pendaftar()
    {
        return $this->hasMany(DB::table('proyek_siswa'), 'proyek_role_id'); 
    }

    public function pendaftar_count()
    {
        return \DB::table('proyek_siswa')->where('proyek_role_id', $this->id)->count();
    }
}
