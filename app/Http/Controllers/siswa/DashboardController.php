<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProyekSiswa;
use App\Models\PengumpulanTugas;
use App\Models\Roadmap;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Proyek_roles;

class DashboardController extends Controller
{
public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        $allRankings = User::where('role', 'siswa')
        ->withSum(['pengumpulanTugas as total_poin' => function($query) {
            $query->where('status', 'diterima');
        }], 'poin_didapat')
        ->orderByDesc('total_poin')
        ->orderBy('name', 'asc') // Tambahkan order name agar konsisten dengan leaderboard
        ->pluck('id')
        ->toArray();

        $myRank = array_search($userId, $allRankings) !== false 
              ? array_search($userId, $allRankings) + 1 
              : '-';

        $myTotalPoin = PengumpulanTugas::where('user_id', $userId)
        ->where('status', 'diterima')
        ->sum('poin_didapat') ?? 0;

        $totalTugas = Roadmap::whereIn('proyek_role_id', function($query) use ($userId) {
            $query->select('proyek_role_id')->from('proyek_siswa')->where('user_id', $userId);
        })->count();

        $tugasSelesai = PengumpulanTugas::where('user_id', $userId)
            ->where('status', 'diterima')
            ->count();
        
        $persentaseTotal = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100) : 0;


        $deadlineTerdekat = ProyekSiswa::with('proyek')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->get()
            ->sortBy(function($item) {
                return $item->proyek->deadline;
            })->first();

 
        $proyekUtama = ProyekSiswa::with(['proyek.guru', 'role'])
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->latest('updated_at')
            ->first();


        $logs = PengumpulanTugas::with('roadmap')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();


        $catatanGuru = PengumpulanTugas::with('proyek.guru')
            ->where('user_id', $userId)
            ->whereNotNull('feedback_guru')
            ->latest('updated_at')
            ->first();

        return view('siswa.dashboard.index', compact(
            'user', 
            'tugasSelesai', 
            'totalTugas', 
            'persentaseTotal', 
            'deadlineTerdekat', 
            'proyekUtama', 
            'logs',
            'catatanGuru',
            'myRank',      
            'myTotalPoin'
        ));
    }
}
