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

        // 1. Ambil proyek yang disetujui + Load milestones untuk hitung progres
        $approvedProjects = Proposal::with([
                'guru', 
                'anggota.user', 
                'milestones' // Tambahkan ini agar bisa hitung progres di Blade
            ])
            ->where('status', 'disetujui')
            ->where(function($query) use ($userId) {
                $query->where('pengaju_id', $userId) 
                    ->orWhereHas('anggota', function($q) use ($userId) { 
                        $q->where('user_id', $userId)
                            ->where('status_konfirmasi', 'setuju'); 
                    });
            })
            ->latest()
            ->paginate(6, ['*'], 'approved_page')
            ->withQueryString();

        // 2. Ambil proyek yang ditolak
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

        return view('siswa.workspace.show', ['project' => $proyek]);
    }
    public function destroy($id)
    {
        $userId = Auth::id();

        // Cari proyek berdasarkan ID
        $proyek = Proposal::findOrFail($id);

        // Keamanan: Cek apakah user yang login adalah pemilik proyek (pengaju_id)
        if ($proyek->pengaju_id !== $userId) {
            return redirect()->back()->with('error', 'Kamu tidak memiliki izin untuk menghapus proyek ini.');
        }

        // Opsional: Jika kamu ingin proyek yang sudah disetujui tidak bisa dihapus (misal harus oleh admin)
        // if ($proyek->status === 'disetujui') {
        //     return redirect()->back()->with('error', 'Proyek yang sudah disetujui tidak dapat dihapus.');
        // }

        try {
            // Hapus data (Relasi anggota, logbook, dll biasanya terhapus otomatis jika menggunakan onCascadeDelete di migration)
            $proyek->delete();

            return redirect()->route('siswa.workspace.index')->with('success', 'Workspace berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
