<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyeks;
use App\Models\Proyek_roles;
use App\Models\Roadmap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProyekController extends Controller
{
    public function index(Request $request)
    {
        $query = Proyeks::where('guru_id', Auth::id());
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_proyek', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('kelas', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('deskripsi', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        $proyeks = $query->latest()->paginate(6)->withQueryString();
        return view('guru.project.index', compact('proyeks'));
    }
    public function create()
    {
        return view('guru.project.create');
    }
    public function edit($id)
    {
        // Pastikan hanya guru pemilik proyek yang bisa edit
        $proyek = Proyeks::where('guru_id', Auth::id())->findOrFail($id);
        
        return view('guru.project.edit', compact('proyek'));
    }
 
    public function store(Request $request)
    {
  
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'kelas'       => 'required',
            'deadline'    => 'required|date',
            'mode'        => 'required|in:individu,kelompok',
            'kesulitan'   => 'required|in:mudah,menengah,sulit',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

   
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('proyek_covers', 'public');
        }

   
        $proyek = Proyeks::create([
            'nama_proyek' => $request->nama_proyek,
            'kelas'       => $request->kelas,
            'deadline'    => $request->deadline,
            'deskripsi'   => $request->deskripsi,
            'cover'       => $coverPath,
            'kesulitan'   => $request->kesulitan,
            'mode'        => $request->mode,
            'max_siswa'   => ($request->mode == 'individu') ? 1 : $request->max_siswa,
            'guru_id'     => Auth::id(),
        ]);

    
        if ($request->mode == 'kelompok') {
           
            $rolesInput = $request->roles ?? ['Peserta']; 
            
            foreach ($rolesInput as $roleName) {
                Proyek_roles::create([
                    'proyek_id' => $proyek->id,
                    'nama_role' => $roleName,
                ]);
            }
        } 
        elseif ($request->mode == 'individu') {
            Proyek_roles::create([
                'proyek_id' => $proyek->id,
                'nama_role' => 'Mandiri',
            ]);
        }
        return redirect()->route('guru.proyek.roadmap', ['id' => $proyek->id, ])
                        ->with('success', 'Proyek berhasil dibuat! Silakan atur roadmap.');
    }
       public function update(Request $request, $id)
        {
            $proyek = Proyeks::where('guru_id', Auth::id())->findOrFail($id);

            $request->validate([
                'nama_proyek' => 'required|string|max:255',
                'kelas'       => 'required',
                'deadline'    => 'required|date',
                'cover'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'kesulitan'   => 'required|in:mudah,menengah,sulit',
            ]);

            $data = $request->only(['nama_proyek', 'kelas', 'deadline', 'deskripsi', 'kesulitan', 'mode', 'max_siswa']);

      
            if ($request->hasFile('cover')) {
                // Hapus cover lama jika ada untuk menghemat storage
                if ($proyek->cover) {
                    Storage::disk('public')->delete($proyek->cover);
                }
                $data['cover'] = $request->file('cover')->store('proyek_covers', 'public');
            }

            $proyek->update($data);

            return redirect()->route('guru.proyek.dashboard')
                            ->with('success', 'Data proyek berhasil diperbarui!');
        }
        public function destroy($id)
        {
        
            $proyek = Proyeks::where('guru_id', Auth::id())->findOrFail($id);

            if ($proyek->cover) {
                Storage::disk('public')->delete($proyek->cover);
            }

            $proyek->delete();

            return redirect()->route('guru.proyek.dashboard')
                            ->with('success', 'Proyek berhasil dihapus secara permanen!');
        }
    public function roadmap(Request $request, $id)
    {
        $proyek = Proyeks::with(['roles.roadmaps'])->findOrFail($id);
        
        $roles = $proyek->roles;

        $hasRoadmap = $roles->contains(function ($role) {
            return $role->roadmaps->isNotEmpty();
        });
        $back_url = null;
        $back_text = null;

        if ($hasRoadmap) {
            $back_url = route('guru.proyek.dashboard');
            $back_text = "Kembali ke Dashboard";
        }
        
        if ($roles->isEmpty()) {
            return redirect()->route('guru.proyek.create')
                            ->with('error', 'Role tidak ditemukan, silakan buat ulang.');
        }
        return view('guru.project.setup', compact('proyek', 'roles', 'back_url', 'back_text'))
         ->with('success', 'Roadmap berhasil dibuat!');
    }
    public function editRoadmap($id)
    {
        $roadmap = Roadmap::findOrFail($id);
        // Kita ambil juga data proyek_role untuk memastikan context-nya benar
        $role = $roadmap->proyekRole; 
        
        return view('guru.project.edit_roadmap', compact('roadmap', 'role'));
    }

    public function updateRoadmap(Request $request, $id)
    {
        $request->validate([
            'judul_tugas'    => 'required|string',
            'instruksi'      => 'required|string',
            'deadline_tugas' => 'required|date',
            'poin'           => 'required|integer',
            'urutan'         => 'required|integer',
        ]);

        $roadmap = Roadmap::findOrFail($id);
        
        $roadmap->update([
            'judul_tugas'    => $request->judul_tugas,
            'instruksi'      => $request->instruksi,
            'deadline_tugas' => $request->deadline_tugas,
            'poin'           => $request->poin,
            'urutan'         => $request->urutan,
        ]);


        $proyekId = $roadmap->Proyek_roles->proyek_id;

        return redirect()->route('guru.proyek.roadmap', $proyekId)
                         ->with('success', 'Tugas berhasil diperbarui!');
    }
    public function storeRoadmap(Request $request)
    {
            $request->validate([
                'proyek_role_id' => 'required|exists:proyek_roles,id',
                'judul_tugas'    => 'required|string',
                'instruksi'      => 'required|string',
            ]);
            $lastOrder = Roadmap::where('proyek_role_id', $request->proyek_role_id)
                        ->max('urutan');

            Roadmap::create([
                'proyek_role_id' => $request->proyek_role_id,
                'judul_tugas'    => $request->judul_tugas,
                'instruksi'      => $request->instruksi,
                'urutan'         => ($lastOrder ?? 0) + 1,
                'deadline_tugas' => $request->deadline_tugas, 
                'poin'           => $request->poin ?? 0,
            ]);

        return back()->with('success', 'Tugas berhasil ditambahkan!');
    }
    public function destroyRoadmap($id)
    {
        $roadmap = Roadmap::findOrFail($id);

        $roadmap->delete();

        return back()->with('success', 'Tugas berhasil dihapus dari timeline!');
    }
    public function uploadTrix(Request $request)
    {
        if ($request->hasFile('file')) {

            $path = $request->file('file')->store('trix', 'public');
            
            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }
    }
}
