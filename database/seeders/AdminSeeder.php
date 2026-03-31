<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
        // 1. Membuat Admin
        User::create([
            'name' => 'Administrator Utama',
            'email' => 'admin@codelab.com',
            'password' => Hash::make('password123'), 
            'role' => 'admin', 
            'identity_number' => 'ADMIN001',
            'class' => null,
            'major' => null,
        ]);

   
        User::create([
            'name' => 'Budi Pengajar, S.Kom',
            'email' => 'guru@codelab.com',
            'password' => Hash::make('password123'), 
            'role' => 'guru', 
            'identity_number' => 'GURU12345', 
            'class' => null,
            'major' => 'Web Development', 
        ]);

   
        User::create([
            'name' => 'Siswa Teladan',
            'email' => 'siswa@codelab.com',
            'password' => Hash::make('password123'), 
            'role' => 'siswa', 
            'identity_number' => '20240001', // NISN
            'class' => 'XI-RPL-1',
            'major' => 'Rekayasa Perangkat Lunak',
        ]);

        $this->command->info('Data Admin, Guru, dan Siswa berhasil dibuat!');
    }
}
