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
    public function logbook($id)
    {
        // Ambil proyek beserta logbook yang diurutkan dari yang terbaru
        $project = Proposal::with([
            'milestones' => function($q) {
                $q->orderBy('deadline', 'asc'); 
            },
            'milestones.logbooks' => function($q) {
                $q->latest(); 
            },
            'milestones.logbooks.user'
        ])->findOrFail($id);

    return view('guru.logbook.index', compact('project'));
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
