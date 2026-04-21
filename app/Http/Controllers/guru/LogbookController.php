<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use App\Models\Milestone;
use App\Models\Logbook;
class LogbookController extends Controller
{
    public function logbook(Request $request, $id) // Tambahkan parameter Request
    {
        $selectedMilestoneId = $request->query('milestone_id');

        $project = Proposal::with([
            'milestones' => function($q) use ($selectedMilestoneId) {
                $q->orderBy('deadline', 'asc');
                // Jika ada milestone yang dipilih, filter di sini
                $q->when($selectedMilestoneId, function($query) use ($selectedMilestoneId) {
                    return $query->where('id', $selectedMilestoneId);
                });
            },
            'milestones.logbooks' => function($q) {
                $q->latest(); 
            },
            'milestones.logbooks.user'
        ])->findOrFail($id);

        // Kita juga butuh daftar semua milestone untuk isi dropdown filter
        $allMilestones = \App\Models\Milestone::where('proposal_id', $id)->get();

        return view('guru.logbook.index', compact('project', 'allMilestones', 'selectedMilestoneId'));
    }
    public function updateFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback_guru' => 'required|string|max:500',
        ]);

        $logbook = Logbook::findOrFail($id);
        $logbook->update([
            'feedback_guru' => $request->feedback_guru
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
