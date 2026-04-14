<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\ProposalMember;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class proposalcontroller extends Controller
{

    public function create()
    {

        $mentors = User::where('role', 'guru')->get();
        
        $siswas = User::where('role', 'siswa')
                      ->where('id', '!=', auth()->id())
                      ->get();

        return view('siswa.proposal.index', compact('mentors', 'siswas'));
    }

    /**
     * Menyimpan data proposal ke database
     */
    public function store(Request $request)
    {

       $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'guru_id' => 'required|exists:users,id',
            'mode' => 'required|in:mandiri,kelompok',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            DB::beginTransaction();


            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('proposals/thumbnails', 'public');
            }


            $proposal = Proposal::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'thumbnail' => $thumbnailPath,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'mode' => $request->mode,
                'status' => 'pending',
                'pengaju_id' => auth()->id(),
                'guru_id' => $request->guru_id,
            ]);

                if ($request->mode === 'kelompok') {
                    $teamData = $request->input('team', []);
                    $leaderIndex = $request->input('leader_selection');

                    foreach ($teamData as $index => $member) {
                        if (!empty($member['user_id'])) {
                            // Tentukan apakah user ini adalah leader
                            $isLeader = ($leaderIndex == $index);

                            proposalmember::create([
                                'proposal_id' => $proposal->id,
                                'user_id' => $member['user_id'],
                                'role' => $member['role'] ?? 'Member',
                                'is_leader' => $isLeader,
                                // PERBAIKAN: Jika leader, otomatis 'setuju'. Jika bukan, 'pending'.
                                'status_konfirmasi' => $isLeader ? 'setuju' : 'pending'
                            ]);
                        }
                    }

            } else {
                ProposalMember::create([
                    'proposal_id' => $proposal->id,
                    'user_id' => auth()->id(),
                    'role' => 'Solo Developer',
                    'is_leader' => true,
                    'status_konfirmasi' => 'setuju'
                ]);
            }

            DB::commit();
            return redirect()->route('siswa.workspace.index')->with('success', 'Proposal proyek berhasil diajukan! Menunggu persetujuan mentor.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($thumbnailPath) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    public function invitations()
    {
        $invitations = ProposalMember::where('user_id', auth()->id())
            ->where('status_konfirmasi', 'pending')
            ->where('is_leader', false) 
            ->with('proposal.pengaju')
            ->latest()
            ->get();

        return view('siswa.proposal.invitations', compact('invitations'));
    }

    public function respondInvitation($id, $status)
    {
        $allowedStatuses = ['setuju', 'ditolak'];
    
        if (!in_array($status, $allowedStatuses)) {
            return back()->with('error', 'Aksi tidak valid.');
        }

        $member = ProposalMember::where('user_id', auth()->id())->findOrFail($id);
        

        $member->update([
            'status_konfirmasi' => $status
        ]);


        $message = ($status == 'setuju') 
            ? 'Kamu resmi bergabung dengan tim!' 
            : 'Undangan berhasil ditolak.';
            
        return back()->with('success', $message);
    }
}
