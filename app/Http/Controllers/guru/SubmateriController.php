<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materis;
use App\Models\SubMateris;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubmateriController extends Controller
{
    public function index($materi_id) 
    {
        // Sekarang $materi_id sudah ada isinya dari URL (misal: /materi/5/submateri)
        $materi = Materis::findOrFail($materi_id);

        $subMateris = SubMateris::where('materi_id', $materi_id)
                                ->orderBy('urutan', 'asc')
                                ->get();

        $stats = [
            'total' => $subMateris->count(),
            'video' => $subMateris->whereNotNull('video_url')->count(),
            'coding' => $subMateris->whereNotNull('instruksi_coding')->count(),
        ];

        return view('guru.submateri.index', compact('materi', 'subMateris', 'stats', 'materi_id'));
    }
    public function create($materi_id) 
    {
        
        $materi = Materis::findOrFail($materi_id);
        $totalSubMateri = SubMateris::where('materi_id', $materi_id)->count();
        $nextUrutan = $totalSubMateri + 1;
       
        return view('guru.submateri.create', compact('materi', 'materi_id', 'nextUrutan'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'materi' => 'required|array',
            'materi.*.judul' => 'required|string|max:255',
            'materi.*.urutan' => 'required|integer',
            'materi.*.pdf_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->materi as $data) {
                $subMateri = new SubMateris();
                $subMateri->materi_id = $request->materi_id;
                $subMateri->judul = $data['judul'];
                $subMateri->kategori = $data['kategori'] ?? 'Web Development';
                $subMateri->urutan = $data['urutan'];
                $subMateri->bacaan = $data['bacaan'];
                $subMateri->video_url = $data['video'];
                $subMateri->instruksi_coding = $data['instruksi'];
                $subMateri->starter_code = $data['kode'];

                if (isset($data['pdf_file'])) {
                    $file = $data['pdf_file'];
                    $path = $file->store('modul_pdf', 'public'); 
                    $subMateri->pdf_path = $path;
                }

                $subMateri->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => count($request->materi) . ' materi berhasil dipublikasikan!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function edit($materi_id, $id)
    {
  
        $subMateri = SubMateris::findOrFail($id);
        
  
        return view('guru.submateri.edit', compact('subMateri', 'materi_id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'urutan' => 'required|integer',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $sub = SubMateris::findOrFail($id);
        
        $sub->judul = $request->judul;
        $sub->kategori = $request->kategori;
        $sub->urutan = $request->urutan;
        $sub->bacaan = $request->bacaan;
        $sub->video_url = $request->video_url;
        $sub->instruksi_coding = $request->instruksi_coding;
        $sub->starter_code = $request->starter_code;

        if ($request->hasFile('pdf_file')) {
            // Hapus PDF lama jika ada
            if ($sub->pdf_path) {
                Storage::disk('public')->delete($sub->pdf_path);
            }
            $sub->pdf_path = $request->file('pdf_file')->store('modul_pdf', 'public');
        }

        $sub->save();

        return redirect()->route('guru.submateri.dashboard', $sub->materi_id)
                        ->with('success', 'Sub materi berhasil diperbarui!');
    }
    public function destroy($id)
    {
        try {
            // 1. Cari submateri yang akan dihapus
            $subMateri = SubMateris::findOrFail($id);
            
            // Simpan ID materi induk untuk keperluan redirect nanti
            $materiIdInduk = $subMateri->materi_id;

            // 2. Hapus file PDF dari storage jika ada
            if ($subMateri->pdf_path) {
                if (Storage::disk('public')->exists($subMateri->pdf_path)) {
                    Storage::disk('public')->delete($subMateri->pdf_path);
                }
            }

            // 3. Hapus data dari database
            $subMateri->delete();

            // 4. Redirect kembali ke dashboard submateri spesifik tadi
            return redirect()->route('guru.submateri.dashboard', $materiIdInduk)
                            ->with('success', 'Sub materi berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus materi: ' . $e->getMessage());
        }
    }
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Simpan ke folder public/uploads/materi
            $path = $file->store('uploads/materi', 'public');
            
            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }
    }
}
