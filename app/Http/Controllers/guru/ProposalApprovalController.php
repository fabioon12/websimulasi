<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use App\Models\ProposalMember;
use App\Models\User;
class ProposalApprovalController extends Controller
{
    public function index()
    {
        $proposals = Proposal::with(['pengaju', 'guru', 'anggota.user'])
            ->where('guru_id', Auth::id())
            ->where('status', 'pending')
            ->whereDoesntHave('anggota', function($query) {
                $query->where('status_konfirmasi', 'pending');
            })
            ->latest()
            ->get();
        return view('guru.proposal.index', compact('proposals'));
    }

    public function approve($id)
    {
        $proposal = Proposal::findOrFail($id);
        
        if($proposal->guru_id !== auth()->id()) return back();

        $proposal->update([
            'status' => 'disetujui'
        ]);

        return redirect()->back()->with('success', 'Proposal telah disetujui dan dipindahkan ke Workspace siswa.');
    }

    public function reject($id)
    {
        $proposal = Proposal::findOrFail($id);
        
        $proposal->update([
            'status' => 'ditolak'
        ]);

        return redirect()->back()->with('info', 'Proposal telah ditolak.');
    }

}
