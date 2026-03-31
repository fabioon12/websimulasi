<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materis;
use App\Models\SubMateris;
use App\Models\ProgressSiswa;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $materiDiambilIds = $user->daftarMateri()->allRelatedIds()->toArray();

      
        $categories = Materis::distinct()->pluck('kategori')->filter();

        
        $query = Materis::withCount('subMateris');
        if ($request->has('category') && $request->category != 'Semua') {
            $query->where('kategori', $request->category);
        }
        $materis = $query->get();

        $materiAktif = $user->daftarMateri()
            ->withCount(['subMateris', 'progress' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->latest('materi_siswa.created_at')
            ->take(3)
            ->get();

        return view('siswa.materi.index', compact('materis', 'materiAktif', 'materiDiambilIds', 'categories'));
    }
    public function enroll($id)
    {
        $user = auth()->user();
       
        $user->daftarMateri()->syncWithoutDetaching([$id]);

        return redirect()->back()->with('success', 'Berhasil mengambil materi!');
    }
    public function learn($id, $sub_id = null)
    {
        $user = auth()->user();
        $materi = Materis::with(['subMateris' => function($query) {
            $query->orderBy('urutan', 'asc');
        }])->findOrFail($id);

        // 1. Ambil ID sub-materi yang SUDAH diselesaikan siswa
        $completedSubIds = ProgressSiswa::where('user_id', $user->id)
                            ->where('materi_id', $id)
                            ->pluck('sub_materi_id')
                            ->toArray();

        // 2. Tentukan sub-materi yang ingin dibuka
        $subMateriAktif = $sub_id 
            ? $materi->subMateris->where('id', $sub_id)->first() 
            : $materi->subMateris->first();

        // 3. LOGIKA PENGUNCI (LOCKER)
        // Cek apakah sub-materi ini adalah sub-materi pertama?
        $isFirst = $subMateriAktif->id === $materi->subMateris->first()->id;

        if (!$isFirst) {
            // Cari sub-materi SEBELUMNYA berdasarkan urutan
            $prevSub = $materi->subMateris->where('urutan', '<', $subMateriAktif->urutan)
                                        ->sortByDesc('urutan')
                                        ->first();

            // Jika bab sebelumnya BELUM diselesaikan, blokir akses
            if ($prevSub && !in_array($prevSub->id, $completedSubIds)) {
                return redirect()->route('siswa.materi.learn', [$id, $prevSub->id])
                                ->with('error', 'Selesaikan bab sebelumnya terlebih dahulu!');
            }
        }

        // 4. Hitung Progres untuk UI
        $totalSub = $materi->subMateris->count();
        $progres = ($totalSub > 0) ? round((count($completedSubIds) / $totalSub) * 100) : 0;

        return view('siswa.materi.show', compact('materi', 'subMateriAktif', 'progres', 'completedSubIds'));
    }
    public function completeSubMateri(Request $request, $materi_id, $sub_id)
    {
        // Catat ke database jika belum ada
        ProgressSiswa::firstOrCreate([
            'user_id' => auth()->id(),
            'materi_id' => $materi_id,
            'sub_materi_id' => $sub_id
        ]);

        // Cari sub-materi berikutnya
        $materi = Materis::findOrFail($materi_id);
        $subAktif = SubMateris::findOrFail($sub_id);
        $nextSub = SubMateris::where('materi_id', $materi_id)
                            ->where('urutan', '>', $subAktif->urutan)
                            ->orderBy('urutan', 'asc')
                            ->first();

        if ($nextSub) {
            return redirect()->route('siswa.materi.learn', [$materi_id, $nextSub->id]);
        }

        return redirect()->route('siswa.materi.dashboard')->with('success', 'Selamat! Kursus selesai.');
    }
    
}
