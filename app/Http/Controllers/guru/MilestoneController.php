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

        $guruId = Auth::id();

        $query = Milestone::with('proposal')
            ->whereHas('proposal', function($q) use ($guruId) {
                $q->where('guru_id', $guruId); 
            })
            ->where('status_review', 'pending'); 


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
        
        $isCompleted = $milestone->is_completed;
        if ($request->status_review === 'revisi') {
            $isCompleted = false;
        }

        $milestone->update([
            'status_review' => $request->status_review,
            'feedback_guru' => $request->feedback_guru,
            'is_completed'  => $isCompleted 
        ]);

        return redirect()->route('guru.milestone.index')->with('success', 'Review milestone berhasil diperbarui!');
    }
}
