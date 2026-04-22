<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use App\Models\ProyekSiswa;
use App\Models\Roadmap;
use App\Models\Proyeks;

use Illuminate\Http\Request;

class ReviewTugasController extends Controller
{
    public function index($proyek_id)
    {
        $proyek = Proyeks::findOrFail($proyek_id);

        // 2. Filter tugas berdasarkan proyek_id yang sedang dibuka
        $tugasMasuk = PengumpulanTugas::with(['user', 'proyek', 'roadmap'])
            ->where('proyek_id', $proyek_id) // Filter krusial
            ->where('status', 'pending')
            ->latest()
            ->get();

        // 3. Kirim variabel $proyek ke view untuk judul/header
        return view('guru.reviewtugas.index', compact('tugasMasuk', 'proyek'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'poin_didapat' => 'required|integer|min:0',
            'feedback_guru' => 'nullable|string'
        ]);

        $tugas = PengumpulanTugas::findOrFail($id);
        

        $tugas->update([
            'status' => $request->status,
            'poin_didapat' => $request->status == 'diterima' ? $request->poin_didapat : 0,
            'feedback_guru' => $request->feedback_guru,
        ]);

        $this->syncSiswaProgress($tugas->user_id, $tugas->proyek_id);

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan!');
    }

    private function syncSiswaProgress($userId, $proyekId)
    {
        $partisipasi = ProyekSiswa::where('user_id', $userId)->where('proyek_id', $proyekId)->first();
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
    
}
