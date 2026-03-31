<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materis; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materis::where('user_id', auth()->id())
                      ->latest()
                      ->get();
        $countPublished = $materis->where('status', 'published')->count();
        $countDraft = $materis->where('status', 'draft')->count();
        return view('guru.materi.index', compact('materis', 'countPublished', 'countDraft'));
    }
    public function create()
    {
        return view('guru.materi.create');
    }
    public function store(Request $request)
    {
 
        $request->validate([
            
            'judul'     => 'required|string|max:255',
            'kategori'  => 'required',
            'deskripsi' => 'required',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            'status'    => 'required|in:published,draft',
        ]);

  
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $namaFile = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            
          
            $path = $file->storeAs('thumbnails', $namaFile, 'public');
        }

        Materis::create([
            'user_id'   => auth()->id(),
            'judul'     => $request->judul,
            'slug'      => Str::slug($request->judul),
            'kategori'  => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'thumbnail' => $path ?? null,
            'status'    => $request->status,
            
        ]);
        $pesan = $request->status == 'published' 
             ? 'Materi berhasil diterbitkan!' 
             : 'Materi berhasil disimpan sebagai draft.';

        return redirect()->route('guru.materi.dashboard')->with('success', 'Materi berhasil diterbitkan!');
    }
    public function edit($id)
    {
        $materi = Materis::where('user_id', auth()->id())->findOrFail($id);
        return view('guru.materi.edit', compact('materi'));
    }

    public function update(Request $request, $id)
    {
        $materi = Materis::where('user_id', auth()->id())->findOrFail($id);


        $request->validate([
            'judul'     => 'required|string|max:255',
            'kategori'  => 'required',
            'deskripsi' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'status'    => 'required|in:published,draft',
        ]);


        $materi->judul     = $request->judul;
        $materi->kategori  = $request->kategori;
        $materi->deskripsi = $request->deskripsi;
        $materi->status    = $request->status; // DI SINI: Draft berubah jadi Published


        if ($request->hasFile('thumbnail')) {
            
            if ($materi->thumbnail && Storage::disk('public')->exists($materi->thumbnail)) {
                Storage::disk('public')->delete($materi->thumbnail);
            }

     
            $file = $request->file('thumbnail');
            $namaFile = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('thumbnails', $namaFile, 'public');
            $materi->thumbnail = $path;
        }

     
        $materi->save();

     
        $pesan = $request->status == 'published' 
                ? 'Materi berhasil diterbitkan!' 
                : 'Materi berhasil disimpan sebagai draft.';

        return redirect()->route('guru.materi.dashboard')->with('success', $pesan);
    }
    
    public function destroy($id)
    {
        $materi = Materis::findOrFail($id);
        // Hapus file thumbnail dari storage jika ada
        if ($materi->thumbnail) {
            Storage::disk('public')->delete($materi->thumbnail);
        }
        $materi->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus!');
    }

}
