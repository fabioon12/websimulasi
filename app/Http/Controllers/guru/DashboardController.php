<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Proyeks;
use App\Models\PengumpulanTugas;
use App\Models\Proyek_roles;
use App\Models\ProyekSiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index()
    {
       $guruId = Auth::id();

        $totalSiswa = ProyekSiswa::whereHas('proyek', function($q) use ($guruId) {
            $q->where('guru_id', $guruId);
        })->distinct('user_id')->count();

    
        $proyekAktif = Proyeks::where('guru_id', $guruId)->count();
        
 
        $tugasPending = PengumpulanTugas::whereHas('proyek', function($q) use ($guruId) {
            $q->where('guru_id', $guruId);
        })->where('status', 'pending')->count();

    
        $recentSubmissions = PengumpulanTugas::with(['user', 'proyek', 'roadmap'])
            ->whereHas('proyek', function($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })
            ->latest()
            ->take(5)
            ->get();

        $statsChart = [
            'diterima' => PengumpulanTugas::where('status', 'diterima')->count(),
            'pending' => $tugasPending,
            'ditolak' => PengumpulanTugas::where('status', 'ditolak')->count(),
        ];

  
        $roleDistribution = ProyekSiswa::whereHas('proyek', function($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })
            ->join('proyek_roles', 'proyek_siswa.proyek_role_id', '=', 'proyek_roles.id')
            ->select('proyek_roles.nama_role', DB::raw('count(*) as total'))
            ->groupBy('proyek_roles.nama_role')
            ->get();

        $avgProgress =ProyekSiswa::avg('progress') ?? 0;
        $avgProgress = round($avgProgress);
        return view('guru.dashboard.index', compact(
            'totalSiswa', 
            'proyekAktif', 
            'tugasPending', 
            'recentSubmissions', 
            'statsChart',
            'roleDistribution',
            'avgProgress'
        ));
    }
}
