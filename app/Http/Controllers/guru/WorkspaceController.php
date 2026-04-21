<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
Use Illuminate\Support\Facades\DB;
use App\Models\User;

class WorkspaceController extends Controller
{
    public function index()
    {
        $pendingProposals = Proposal::where('status', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'pending_page');


        $activeProjects = Proposal::where('status', 'disetujui')
            ->with(['milestones', 'logbooks']) 
            ->latest()
            ->paginate(6, ['*'], 'active_page');


        $activeProjectIds = Proposal::where('status', 'disetujui')->pluck('id');

        $totalMilestones = \App\Models\Milestone::whereIn('proposal_id', $activeProjectIds)->count();

        $completedMilestones = \App\Models\Milestone::whereIn('proposal_id', $activeProjectIds)
            ->where('is_completed', true)
            ->where('status_review', 'disetujui')
            ->count();

        $averageProgress = $totalMilestones > 0 
            ? round(($completedMilestones / $totalMilestones) * 100) 
            : 0;

        $totalStudents = User::where('role', 'siswa')->count();

        return view('guru.proposal.dashboard', compact(
            'pendingProposals', 
            'activeProjects', 
            'totalStudents', 
            'averageProgress' 
        ));
    }
}
