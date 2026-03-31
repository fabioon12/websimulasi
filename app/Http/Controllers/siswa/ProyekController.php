<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProyekSiswa; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Roadmap;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Proyek_roles;

class ProyekController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
 
        $statusFilter = $request->get('status', 'active'); 

  
        $proyekAktif = ProyekSiswa::with(['proyek.guru', 'role'])
            ->where('user_id', $userId)
            ->when($statusFilter == 'active', function($query) {
           
                return $query->where('progress', '<', 100);
            })
            ->when($statusFilter == 'completed', function($query) {
              
                return $query->where('progress', '>=', 100);
            })
            ->latest()
            ->get();

  
        $totalPoinSiswa = DB::table('pengumpulan_tugas')
            ->where('user_id', $userId)
            ->where('status', 'diterima')
            ->sum('poin_didapat') ?? 0;

        $rank = DB::table('users')
            ->leftJoin('pengumpulan_tugas', function($join) {
                $join->on('users.id', '=', 'pengumpulan_tugas.user_id')
                    ->where('pengumpulan_tugas.status', '=', 'diterima');
            })
            ->select('users.id')
            ->selectRaw('SUM(COALESCE(pengumpulan_tugas.poin_didapat, 0)) as total_poin')
            ->groupBy('users.id')
            ->orderByDesc('total_poin')
            ->get()
            ->pluck('id')
            ->search($userId) + 1;


        $counts = [
            'active' => ProyekSiswa::where('user_id', $userId)->where('progress', '<', 100)->count(),
            'completed' => ProyekSiswa::where('user_id', $userId)->where('progress', '>=', 100)->count(),
        ];

        return view('siswa.proyek.index', compact('proyekAktif', 'totalPoinSiswa', 'rank', 'counts'));
    }
    public function show($id)
    {
        $userId = Auth::id();


        
        $partisipasi = ProyekSiswa::with(['proyek.guru', 'role'])
            ->where('proyek_id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $deadline = Carbon::parse($partisipasi->proyek->deadline);
        $now = Carbon::now();

        if ($now->greaterThan($deadline) && $partisipasi->progress < 100) {
         return redirect()->route('siswa.proyek.index')
            ->with('error', 'Waktu pengerjaan untuk proyek ini telah berakhir.');
        }

        $roadmaps = Roadmap::where('proyek_role_id', $partisipasi->proyek_role_id)
            ->orderBy('urutan', 'asc')
            ->get();

        $tugasDikumpulkan = PengumpulanTugas::where('user_id', $userId)
            ->where('proyek_id', $id)
            ->get()
            ->keyBy('roadmap_id'); 

        return view('siswa.proyek.workspace', compact('partisipasi', 'roadmaps', 'tugasDikumpulkan'));
    }
    public function store(Request $request)
    {
   
        $request->validate([
            'proyek_id'  => 'required|exists:proyeks,id',
            'roadmap_id' => 'required|exists:roadmaps,id',
            'link_repo'  => 'required|url',
            'catatan_siswa' => 'nullable|string',
        ]);

        $userId = Auth::id();


        $tugas = PengumpulanTugas::where('user_id', $userId)
            ->where('roadmap_id', $request->roadmap_id)
            ->first();

        if ($tugas && $tugas->status == 'diterima') {
            return redirect()->back()->with('error', 'Tugas ini sudah diterima dan tidak bisa diubah lagi.');
        }


        PengumpulanTugas::updateOrCreate(
            [
                'user_id'    => $userId,
                'roadmap_id' => $request->roadmap_id,
                'proyek_id'  => $request->proyek_id,
            ],
            [
                'link_repo'     => $request->link_repo,
                'catatan_siswa' => $request->catatan_siswa,
                'status'        => 'pending', 
                'poin_didapat'  => 0,         
            ]
        );

        $this->updateProyekProgress($userId, $request->proyek_id);

        return redirect()->back()->with('success', 'Tugas berhasil dikirim! Menunggu review dari mentor.');
    }

    /**
     * Helper function untuk menghitung persentase progress
     */
    private function updateProyekProgress($userId, $proyekId)
    {
        $partisipasi = ProyekSiswa::where('user_id', $userId)
            ->where('proyek_id', $proyekId)
            ->first();

        if ($partisipasi) {

            $totalTugas = Roadmap::where('proyek_role_id', $partisipasi->proyek_role_id)->count();
            

            $tugasSelesai = PengumpulanTugas::where('user_id', $userId)
                ->where('proyek_id', $proyekId)
                ->where('status', 'diterima')
                ->count();

            $progress = $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100) : 0;

            $partisipasi->update(['progress' => $progress]);
        }
    }
    public function uploadTrix(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
              
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
             
                $path = $file->storeAs('tugas-attachments', $filename, 'public');
                
             
                $url = asset('storage/' . $path); 

                return response()->json([
                    'url' => $url
                ], 200);
            }
            return response()->json(['error' => 'No file uploaded'], 400);
        } catch (\Exception $e) {
         
            \Log::error('Trix Upload Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
