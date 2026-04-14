<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Milestone;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
class MilestoneController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil ID guru yang sedang login
        $guruId = Auth::id();

        // 2. Query Milestone yang statusnya masih 'pending'
        // Baik itu pending rencana awal maupun pending setelah diklik 'selesai'
        $query = Milestone::with('proposal')
            ->whereHas('proposal', function($q) use ($guruId) {
                $q->where('guru_id', $guruId); // Pastikan hanya milik bimbingan guru ini
            })
            ->where('status_review', 'pending'); 

        // 3. Filter jika datang dari tombol 'Lihat' di Monitoring
        if ($request->has('project_id')) {
            $query->where('proposal_id', $request->project_id);
        }

        $pendingMilestones = $query->latest()->get();

        return view('guru.milestone.index', compact('pendingMilestones'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_review' => 'required|in:disetujui,revisi',
            'feedback_guru' => 'required|string|min:5',
        ]);

        $milestone = Milestone::findOrFail($id);
        
        $newStatus = ($request->status_review === 'disetujui');

        $milestone->update([
            'status_review' => $request->status_review,
            'feedback_guru' => $request->feedback_guru,
            'is_completed'  => $newStatus 
        ]);

        return redirect()->route('guru.milestone.index')->with('success', 'Review milestone berhasil diperbarui!');
    }
}
