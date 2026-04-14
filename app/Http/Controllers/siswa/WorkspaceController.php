<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use App\Models\ProposalMember;
use App\Models\User;
use Carbon\Carbon;

class WorkspaceController extends Controller
{
    public function index()
    {
       $userId = auth()->id();


        $approvedProjects = Proposal::with(['guru', 'anggota.user'])
            ->where('status', 'disetujui')
            ->where(function($query) use ($userId) {
                $query->where('pengaju_id', $userId) // Jika saya ketua
                    ->orWhereHas('anggota', function($q) use ($userId) { // Atau jika saya anggota
                        $q->where('user_id', $userId)
                            ->where('status_konfirmasi', 'setuju'); // Sudah acc undangan
                    });
            })
            ->latest()
            ->paginate(6, ['*'], 'approved_page')
            ->withQueryString();


        $rejectedProjects = Proposal::where('status', 'ditolak')
            ->where('pengaju_id', $userId)
            ->latest()
            ->paginate(6, ['*'], 'rejected_page')
            ->withQueryString();

        return view('siswa.workspace.dashboard', compact('approvedProjects', 'rejectedProjects'));
    }

    /**
     * Menampilkan detail progres proyek tertentu
     */
    public function show($id)
    {
        $userId = Auth::id();

    // Tambahkan 'logbooks' ke dalam Eager Loading
        $proyek = Proposal::with(['guru', 'pengaju', 'anggota.user', 'logbooks' => function($q) {
                $q->latest(); // Supaya logbook terbaru muncul paling atas
            }])
            ->where(function($query) use ($userId) {
                $query->where('pengaju_id', $userId)
                    ->orWhereHas('anggota', function($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
            })
            ->findOrFail($id);

        if ($proyek->status !== 'disetujui') {
            return redirect()->route('siswa.workspace.index')
                ->with('error', 'Akses ditolak. Proyek ini belum disetujui oleh mentor.');
        }

        // Kita tetap kirim dengan nama 'project' agar cocok dengan View sebelumnya
        // Atau kamu bisa ganti di View dari $project menjadi $proyek
        return view('siswa.workspace.show', ['project' => $proyek]);
    }

}
