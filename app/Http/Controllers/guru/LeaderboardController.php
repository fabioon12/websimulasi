<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Mengambil data siswa dengan total poin dari tugas yang 'diterima'
        $leaderboard = User::where('role', 'siswa')
            ->withSum(['pengumpulanTugas as total_poin' => function($query) {
                $query->where('status', 'diterima');
            }], 'poin_didapat')
            // Tambahan: Hitung berapa banyak tugas yang sudah diselesaikan
            ->withCount(['pengumpulanTugas as tugas_selesai' => function($query) {
                $query->where('status', 'diterima');
            }])
            ->orderByDesc('total_poin')
            ->orderBy('name', 'asc')
            ->paginate(20);

        // Statistik Ringkasan untuk Admin
        $stats = [
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_poin_beredar' => DB::table('pengumpulan_tugas')->where('status', 'diterima')->sum('poin_didapat'),
            'rata_rata_poin' => $leaderboard->avg('total_poin')
        ];

        return view('guru.leaderboard.index', compact('leaderboard', 'stats'));
    }
}
