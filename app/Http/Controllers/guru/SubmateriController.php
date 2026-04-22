<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materis;
use App\Models\SubMateris;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\SubMateriKuis;

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
            'kuis'   => $subMateris->where('tipe', 'kuis')->count(),
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
            'materi.*.tipe' => 'required|in:materi,kuis', // Tambahkan validasi tipe
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->materi as $data) {
                $subMateri = new SubMateris();
                $subMateri->materi_id = $request->materi_id;
                $subMateri->judul = $data['judul'];
                $subMateri->tipe = $data['tipe']; 
                $subMateri->kategori = $data['kategori'] ?? 'Web Development';
                $subMateri->urutan = $data['urutan'];
                
                // Data materi hanya diisi jika tipe adalah 'materi'
                $subMateri->bacaan = $data['bacaan'] ?? null;
                $subMateri->video_url = $data['video'] ?? null;
                $subMateri->instruksi_coding = $data['instruksi'] ?? null;
                $subMateri->starter_code = $data['kode'] ?? null;

                if (isset($data['pdf_file'])) {
                    $subMateri->pdf_path = $data['pdf_file']->store('modul_pdf', 'public');
                }

                $subMateri->save();

                // --- BAGIAN BARU: SIMPAN SOAL KUIS ---
                if ($data['tipe'] === 'kuis' && isset($data['kuis_data'])) {
                    $questions = is_array($data['kuis_data']) ? $data['kuis_data'] : json_decode($data['kuis_data'], true);
                    
                    foreach ($questions as $q) {
                        SubMateriKuis::create([
                            'sub_materi_id' => $subMateri->id,
                            'pertanyaan'    => $q['pertanyaan'],
                            'gambar_pertanyaan' => $q['gambar_pertanyaan'] ?? null, // Simpan URL Gambar
                            'point'         => $q['point'] ?? 10,               // Simpan Poin
                            'opsi_a'        => $q['opsi_a'],
                            'opsi_a_img'    => $q['opsi_a_img'] ?? null,        // Gambar Opsi A
                            'opsi_b'        => $q['opsi_b'],
                            'opsi_b_img'    => $q['opsi_b_img'] ?? null,        // Gambar Opsi B
                            'opsi_c'        => $q['opsi_c'],
                            'opsi_c_img'    => $q['opsi_c_img'] ?? null,        // Gambar Opsi C
                            'opsi_d'        => $q['opsi_d'],
                            'opsi_d_img'    => $q['opsi_d_img'] ?? null,        // Gambar Opsi D
                            'jawaban'       => $q['jawaban'],
                            
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function edit($materi_id, $id)
    {
  
        $subMateri = SubMateris::with('kuis')->findOrFail($id);
        return view('guru.submateri.edit', compact('subMateri', 'materi_id'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'judul' => 'required|string|max:255',
                'urutan' => 'required|integer',
                'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
                'tipe' => 'required|in:materi,kuis',
                // Tambahkan kategori jika memang dikirim
                'kategori' => 'nullable|string', 
            ]);

            $sub = SubMateris::findOrFail($id);
            $sub->judul = $request->judul;
            $sub->kategori = $request->kategori;
            $sub->urutan = $request->urutan;
            $sub->tipe = $request->tipe;

            if ($request->tipe !== 'kuis') {
                // PASTIKAN: nama field di FormData JS adalah 'bacaan'
                $sub->bacaan = $request->bacaan;
                $sub->video_url = $request->video_url; 
                $sub->instruksi_coding = $request->instruksi_coding;
                $sub->starter_code = $request->starter_code;
                
                if ($request->hasFile('pdf_file')) {
                    if ($sub->pdf_path) {
                        Storage::disk('public')->delete($sub->pdf_path);
                    }
                    $sub->pdf_path = $request->file('pdf_file')->store('modul_pdf', 'public');
                }
            } else {
                // Hapus kuis lama
                $sub->kuis()->delete(); 

                // Handle kuis_data yang dikirim sebagai string JSON
                $questions = $request->kuis_data;
                if (is_string($questions)) {
                    $questions = json_decode($questions, true);
                }

                if (!empty($questions) && is_array($questions)) {
                    foreach ($questions as $q) {
                        SubMateriKuis::create([
                            'sub_materi_id'     => $sub->id,
                            'pertanyaan'        => $q['pertanyaan'],
                            'gambar_pertanyaan' => $q['gambar_pertanyaan'] ?? null,
                            'point'             => $q['point'] ?? 10,
                            'opsi_a'            => $q['opsi_a'],
                            'opsi_a_img'        => $q['opsi_a_img'] ?? null,
                            'opsi_b'            => $q['opsi_b'],
                            'opsi_b_img'        => $q['opsi_b_img'] ?? null,
                            'opsi_c'            => $q['opsi_c'],
                            'opsi_c_img'        => $q['opsi_c_img'] ?? null,
                            'opsi_d'            => $q['opsi_d'],
                            'opsi_d_img'        => $q['opsi_d_img'] ?? null,
                            'jawaban'           => $q['jawaban'],
                        ]);
                    }
                }
            }

            $sub->save();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Materi dan Kuis berhasil diperbarui',
                'materi_id' => $sub->materi_id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // DEBUG: Sangat penting untuk melihat pesan error asli jika gagal
            return response()->json([
                'status' => 'error', 
                'message' => 'Gagal Update: ' . $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {

            $subMateri = SubMateris::findOrFail($id);
            
    
            $materiIdInduk = $subMateri->materi_id;

   
            if ($subMateri->pdf_path) {
                if (Storage::disk('public')->exists($subMateri->pdf_path)) {
                    Storage::disk('public')->delete($subMateri->pdf_path);
                }
            }

     
            $subMateri->delete();

  
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
            $path = $file->store('uploads/materi', 'public');
            
            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }
    }
    public function uploadAttachment(Request $request)
    {
        if ($request->hasFile('file')) {
            // Simpan file ke folder 'public/attachments'
            $path = $request->file('file')->store('attachments', 'public');
            
            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }
        
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
