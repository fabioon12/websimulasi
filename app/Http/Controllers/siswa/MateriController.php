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

      
        $categories = Materis::where('status', 'published')->distinct()->pluck('kategori')->filter();

        $query = Materis::where('status', 'published')->withCount('subMateris');
        if ($request->has('category') && $request->category != 'Semua') {
            $query->where('kategori', $request->category);
        }
        $materis = $query->get();

        $materiAktif = $user->daftarMateri()
            ->where('status', 'published')
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
        $materi = Materis::where('status', 'published')->findOrFail($id);
        $user->daftarMateri()->syncWithoutDetaching([$id]);

        return redirect()->back()->with('success', 'Berhasil mengambil materi!');
    }
    public function learn($id, $sub_id = null)
    {
        $user = auth()->user();
        
        // 1. Eager Load subMateris DAN kuisnya sekaligus
        $materi = Materis::where('status', 'published')
                ->with(['subMateris' => function($query) {
                    $query->orderBy('urutan', 'asc');
                }, 'subMateris.kuis']) 
                ->findOrFail($id);

        // 2. Ambil progres siswa
        $completedSubIds = ProgressSiswa::where('user_id', $user->id)
                            ->where('materi_id', $id)
                            ->pluck('sub_materi_id')
                            ->toArray();

        // 3. Tentukan Sub Materi yang sedang dibuka
        $subMateriAktif = $sub_id 
            ? $materi->subMateris->firstWhere('id', $sub_id) // Lebih efisien daripada where()->first()
            : $materi->subMateris->first();

        // PROTEKSI: Jika ID ngawur/tidak ditemukan
        if (!$subMateriAktif) {
            return redirect()->route('siswa.materi.learn', $id)
                            ->with('error', 'Bab tidak ditemukan.');
        }

        // 4. Validasi Prasyarat (Bab sebelumnya harus selesai)
        $isFirst = $subMateriAktif->id === $materi->subMateris->first()->id;
        if (!$isFirst) {
            $prevSub = $materi->subMateris->where('urutan', '<', $subMateriAktif->urutan)
                                        ->sortByDesc('urutan')
                                        ->first();

            if ($prevSub && !in_array($prevSub->id, $completedSubIds)) {
                return redirect()->route('siswa.materi.learn', [$id, $prevSub->id])
                                ->with('error', 'Selesaikan bab sebelumnya terlebih dahulu!');
            }
        }

        // 5. Siapkan data kuis (hanya jika tipe adalah kuis)
        $dataKuis = ($subMateriAktif->tipe === 'kuis') 
                    ? $subMateriAktif->kuis->toArray() 
                    : [];

        // 6. Hitung persentase progres
        $totalSub = $materi->subMateris->count();
        $progres = ($totalSub > 0) ? round((count($completedSubIds) / $totalSub) * 100) : 0;

        return view('siswa.materi.show', compact(
            'materi', 
            'subMateriAktif', 
            'progres', 
            'completedSubIds', 
            'dataKuis'
        ));
    }
    public function completeSubMateri(Request $request, $materi_id, $sub_id)
    {

        ProgressSiswa::firstOrCreate([
            'user_id' => auth()->id(),
            'materi_id' => $materi_id,
            'sub_materi_id' => $sub_id
        ]);


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
