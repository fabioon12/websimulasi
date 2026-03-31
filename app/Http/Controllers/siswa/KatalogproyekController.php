<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyeks;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Proyek_roles;
use Illuminate\Support\Facades\DB;

class KatalogproyekController extends Controller
{
    public function index(Request $request)
    {
 
        $query = Proyeks::with(['roles', 'guru']);


        if ($request->filled('search')) {
            $query->where('nama_proyek', 'like', '%' . $request->search . '%');
        }

  
        if ($request->filled('difficulty') && $request->difficulty != 'Semua Tingkat') {
            $query->where('kesulitan', strtolower($request->difficulty));
        }


        $proyeks = $query->latest()->paginate(9);

    
        $proyeks->getCollection()->transform(function ($proyek) {
            $deadline = \Carbon\Carbon::parse($proyek->deadline);
            $sekarang = \Carbon\Carbon::now();
            
            $proyek->sisa_hari = (int) $sekarang->diffInDays($deadline, false);
            

            if ($proyek->sisa_hari < 0) {
                $proyek->time_label = "Waktu Habis";
                $proyek->time_status = "expired";
            } elseif ($proyek->sisa_hari == 0) {
                $proyek->time_label = "Terakhir Hari Ini";
                $proyek->time_status = "urgent";
            } elseif ($proyek->sisa_hari <= 7) {
                $proyek->time_label = $proyek->sisa_hari . " Hari Lagi";
                $proyek->time_status = "urgent";
            } else {
                $proyek->time_label = $proyek->sisa_hari . " Hari Lagi";
                $proyek->time_status = "safe";
            }

            return $proyek;
        });

        return view('siswa.katalog.index', [
            'proyeks' => $proyeks
        ]);
    }
    public function show($id)
    {
        $proyek = Proyeks::with(['guru', 'roles.roadmaps' => function($query) {
            $query->orderBy('urutan', 'asc');
        }])->findOrFail($id);

        $totalPoin = 0;
        foreach($proyek->roles as $role) {
            $totalPoin += $role->roadmaps->sum('poin');
        }

        return view('siswa.katalog.show', compact('proyek', 'totalPoin'));
    }

    private function getRandomGradient($id)
    {
        $gradients = [
            'from-blue-500 to-indigo-600',
            'from-emerald-400 to-teal-600',
            'from-rose-400 to-orange-500',
            'from-purple-500 to-pink-600',
            'from-slate-700 to-slate-900'
        ];
        return $gradients[$id % count($gradients)];
    }
    public function join(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required|exists:proyeks,id',
            'proyek_role_id' => 'required|exists:proyek_roles,id',
        ]);

        $userId = Auth::id();
        $proyekId = $request->proyek_id;
        $roleId = $request->proyek_role_id;

        $sudahJoin = DB::table('proyek_siswa')
            ->where('proyek_id', $proyekId)
            ->where('user_id', $userId)
            ->exists();

        if ($sudahJoin) {
            return redirect()->back()->with('error', 'Kamu sudah bergabung dalam proyek ini!');
        }


        $proyek = Proyeks::findOrFail($proyekId);
        $jumlahPeserta = DB::table('proyek_siswa')->where('proyek_id', $proyekId)->count();
        
        if ($jumlahPeserta >= $proyek->max_siswa) {
            return redirect()->back()->with('error', 'Maaf, kuota proyek ini sudah penuh.');
        }


        DB::beginTransaction();
        try {
            DB::table('proyek_siswa')->insert([
                'user_id' => $userId,
                'proyek_id' => $proyekId,
                'proyek_role_id' => $roleId,
                'status' => 'active', 
                'progress' => 0,      
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('siswa.proyek.pengerjaan', $proyekId)
                ->with('success', 'Selamat! Kamu berhasil bergabung sebagai ' . Proyek_roles::find($roleId)->nama_role);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal bergabung, coba lagi nanti.');
        }
    }
}
