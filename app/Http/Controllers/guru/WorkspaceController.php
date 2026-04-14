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
            ->latest()
            ->paginate(6, ['*'], 'active_page');

        $totalStudents = User::where('role', 'siswa')->count(); // Opsional untuk statistik

        return view('guru.proposal.dashboard', compact('pendingProposals', 'activeProjects', 'totalStudents'));
    }
}
