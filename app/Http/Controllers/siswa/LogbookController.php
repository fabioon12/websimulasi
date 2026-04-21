<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Logbook;
use App\Models\Milestone;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    public function index(Request $request, $id) // Tambahkan Request $request
    {
        $userId = Auth::id();
        $selectedMilestoneId = $request->query('milestone_id');

        // Ambil proyek dengan filter pada relasi logbooks
        $project = Proposal::with([
            'logbooks' => function($q) use ($selectedMilestoneId) {
                // Filter berdasarkan milestone jika dipilih
                $q->when($selectedMilestoneId, function($query) use ($selectedMilestoneId) {
                    return $query->where('milestone_id', $selectedMilestoneId);
                });
                $q->orderBy('tanggal_kerjakan', 'desc');
                $q->with(['user', 'milestone']); // Load relasi logbook
            },
            'milestones' => function($q) {
                $q->where('status_review', 'disetujui');
            }
        ])
        ->where(function($query) use ($userId) {
            $query->where('pengaju_id', $userId)
                ->orWhereHas('anggota', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
        })
        ->findOrFail($id);

        // Ambil daftar semua milestone yang disetujui untuk isi dropdown filter
        $allMilestones = $project->milestones;

        return view('siswa.workspace.logbook', compact('project', 'allMilestones', 'selectedMilestoneId'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
        'milestone_id' => 'required|exists:milestones,id',
        'judul' => 'required|string|max:150',
        'deskripsi' => 'required|string',
        'tanggal_kerjakan' => 'required|date',
        'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);


        
        $project = Proposal::findOrFail($id);

        if (now()->greaterThan($project->tanggal_selesai)) {
            return redirect()->back()->with('error', 'Gagal! Batas waktu pengerjaan proyek ini sudah berakhir. Anda tidak dapat menambah logbook baru.');
        }
        $data = [
            'proposal_id' => $project->id,
            'milestone_id' => $request->milestone_id,
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_kerjakan' => $request->tanggal_kerjakan,
        ];

        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('logbooks', 'public');
            $data['lampiran'] = $path;
            $data['file_type'] = $request->file('lampiran')->getClientOriginalExtension() == 'pdf' ? 'pdf' : 'image';
        }

        $save = Logbook::create($data);

        if($save) {
            return redirect()->back()->with('success', 'Berhasil disimpan!');
        } else {
            dd("Gagal simpan ke database, cek model fillable");
        }
    }
}
