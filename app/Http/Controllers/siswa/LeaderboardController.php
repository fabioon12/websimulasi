<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProyekSiswa;
use App\Models\PengumpulanTugas;

class LeaderboardController extends Controller
{
    public function index()
    {
    $user = Auth::user();

        $leaderboard = User::where('role', 'siswa')
            ->select('id', 'name', 'class', 'major') 
            ->withSum(['PengumpulanTugas as total_poin' => function($query) {
                $query->where('status', 'diterima');
            }], 'poin_didapat')
            ->orderByDesc('total_poin')
            ->orderBy('name', 'asc')
            ->paginate(15);

 
        $allRankings = User::where('role', 'siswa')
            ->withSum(['pengumpulanTugas as total_poin' => function($query) {
                $query->where('status', 'diterima');
            }], 'poin_didapat')
            ->orderByDesc('total_poin')
            ->pluck('id')
            ->toArray();
            
        $myRank = array_search($user->id, $allRankings) !== false 
                  ? array_search($user->id, $allRankings) + 1 
                  : '-';

 
        $myTotalPoin = DB::table('pengumpulan_tugas')
            ->where('user_id', $user->id)
            ->where('status', 'diterima')
            ->sum('poin_didapat') ?? 0;

        return view('siswa.leaderboard.index', compact(
            'leaderboard', 
            'myRank', 
            'user', 
            'myTotalPoin'
        ));
    }
}
