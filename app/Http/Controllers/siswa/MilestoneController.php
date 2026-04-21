<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Milestone;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MilestoneController extends Controller
{
    public function index($id)
    {
        $userId = Auth::id();


        $project = Proposal::with(['milestones' => function($q) {
            $q->orderBy('deadline', 'asc');
        }])
        ->where(function($query) use ($userId) {
            $query->where('pengaju_id', $userId)
                  ->orWhereHas('anggota', function($q) use ($userId) {
                      $q->where('user_id', $userId);
                  });
        })
        ->findOrFail($id);


        return view('siswa.workspace.workspace', compact('project'));
    }

    /**
     * Menyimpan Milestone baru (Hanya oleh Ketua)
     */
    public function store(Request $request, $id)
    {
        $userId = Auth::id();

        $project = Proposal::where('id', $id)
            ->where('pengaju_id', $userId)
            ->firstOrFail();

        if (now()->greaterThan($project->tanggal_selesai)) {
            return redirect()->back()->with('error', 'Waktu pengerjaan proyek telah habis. Anda hanya dapat meninjau data (Review Only).');
        }
        $request->validate([
            'nama_milestone' => 'required|string|max:255',
            'deadline' => 'required|date', 
        ]);


        Milestone::create([
            'proposal_id'    => $project->id,
            'nama_milestone' => $request->nama_milestone,
            'deadline'       => $request->deadline,
            'status_review'  => 'pending',
            'is_completed'   => false, 
        ]);

        return redirect()->back()->with('success', 'Target baru berhasil ditambahkan!');
    }

    /**
     * Menandai Milestone sebagai selesai oleh siswa
     */
    public function complete($id)
    {
        $milestone = Milestone::whereHas('proposal', function($q) {
            $q->where('pengaju_id', Auth::id());
        })->findOrFail($id);


        if ($milestone->status_review !== 'disetujui') {
            return redirect()->back()->with('error', 'Milestone ini belum disetujui oleh Guru/Mentor.');
        }

        $milestone->update([
            'is_completed' => true,
            'status_review' => 'pending' 
        ]);

        return redirect()->back()->with('success', 'Laporan pengerjaan milestone terkirim! Menunggu validasi akhir mentor.');
    }
}
