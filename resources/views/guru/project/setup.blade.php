@extends('guru.layouts.app')

@section('title', 'Atur Roadmap Role - CodeLab')

@section('content')
{{-- Load Trix Assets --}}
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

<style>
    /* Custom Trix Styling */
    trix-editor {
        border: none !important;
        background-color: #f8fafc !important;
        border-radius: 1.5rem !important;
        padding: 1.25rem !important;
        min-height: 250px !important; /* Ditinggikan agar nyaman mengetik */
        font-size: 0.875rem !important;
        outline: none !important;
    }

    /* Pastikan tombol gambar MUNCUL */
    .trix-button--icon-attach { display: inline-block !important; }
    
    /* Styling Heading 1 agar terlihat beda di editor */
    trix-editor h1 {
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        color: #1e293b !important;
        margin-bottom: 0.5rem !important;
    }

    /* Membuat Gambar Menjadi Grid 3 Kolom */
    trix-editor figure {
        display: inline-block !important;
        width: 32% !important; /* Sekitar 3 kolom */
        margin: 0.5% !important;
        vertical-align: top !important;
        padding: 0 !important;
    }

    trix-editor figure img {
        width: 100% !important;
        height: auto !important;
        border-radius: 1rem !important;
        object-fit: cover;
    }

    /* List Styling */
    trix-editor ul { list-style-type: disc !important; margin-left: 1rem !important; }
    trix-editor ol { list-style-type: decimal !important; margin-left: 1rem !important; }
    .instruction-content {
        line-height: 1.8;
        color: #475569; /* slate-600 */
    }

    /* Styling Heading 1 di Timeline */
    .instruction-content h1 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
        text-transform: uppercase;
    }

    /* GRID GAMBAR: 3 Kolom di Timeline */
    /* Trix membungkus gambar dalam tag <figure> */
    .instruction-content figure {
        display: inline-block !important;
        width: 31% !important; /* 3 kolom */
        margin: 1% !important;
        vertical-align: top;
    }

    .instruction-content figure img {
        width: 100%;
        height: 150px; /* Tinggi seragam agar rapi */
        object-fit: cover;
        border-radius: 1.25rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        transition: transform 0.3s ease;
    }

    .instruction-content figure img:hover {
        transform: scale(1.05);
    }

    /* List Styling agar peluru muncul */
    .instruction-content ul { 
        list-style-type: disc !important; 
        margin-left: 1.25rem !important; 
        margin-bottom: 1rem;
    }
    .instruction-content ol { 
        list-style-type: decimal !important; 
        margin-left: 1.25rem !important; 
        margin-bottom: 1rem;
    }
    .instruction-content li {
        margin-bottom: 0.25rem;
    }

    /* Responsive untuk HP (Jadi 2 kolom) */
    @media (max-width: 640px) {
        .instruction-content figure {
            width: 47% !important;
        }
    }
</style>

<div class="max-w-6xl mx-auto space-y-6 pb-20">
    
    {{-- Header & Navigation --}}
    <div class="mb-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- Tombol Kembali ke Edit Proyek (Langkah Sebelumnya) --}}
        <a href="{{ $back_url }}" class="group inline-flex items-center gap-3 text-slate-400 hover:text-indigo-600 transition-all font-black text-[10px] uppercase tracking-[0.2em]">
            <div class="w-10 h-10 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fas fa-arrow-left"></i>
            </div>
            <span>{{ $back_text }}</span>
        </a>

        {{-- Visual Stepper --}}
        <div class="flex items-center gap-4 bg-white px-6 py-2 rounded-full border border-slate-100 shadow-sm">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[8px]"><i class="fas fa-check"></i></div>
                <span class="text-[9px] font-black text-slate-400 uppercase">Detail</span>
            </div>
            <div class="w-6 h-[1px] bg-slate-200"></div>
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[8px]">2</div>
                <span class="text-[9px] font-black text-indigo-900 uppercase italic">Roadmap</span>
            </div>
        </div>
    </div>

    {{-- Project Info Card --}}
    <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-center md:text-left">
            <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">Roadmap: <span class="text-indigo-600">{{ $proyek->nama_proyek }}</span></h1>
            <p class="text-slate-500 font-medium text-xs mt-2 uppercase tracking-widest">Penyusunan Instruksi Kerja Per Role</p>
        </div>
        <div class="flex items-center gap-4 bg-indigo-50 px-6 py-3 rounded-2xl border border-indigo-100">
            <i class="fas fa-calendar-check text-indigo-500"></i>
            <div>
                <p class="text-[9px] font-black text-indigo-400 uppercase leading-none">Deadline Utama</p>
                <p class="text-xs font-bold text-indigo-900 mt-1 uppercase">{{ \Carbon\Carbon::parse($proyek->deadline)->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Role Tabs --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white p-2 rounded-[2.5rem] border border-slate-100 shadow-sm">
        @foreach($roles as $index => $role)
        <button onclick="switchRole('{{ Str::slug($role->nama_role) }}', {{ $role->id }})" 
                id="btn-{{ Str::slug($role->nama_role) }}" 
                class="role-tab flex items-center justify-center gap-3 py-4 rounded-[2rem] {{ $index == 0 ? 'bg-indigo-500 text-white shadow-xl' : 'bg-slate-50 text-slate-400' }} transition-all duration-300">
            <i class="fas fa-user-tag text-sm"></i>
            <span class="text-xs font-black uppercase tracking-widest">{{ $role->nama_role }}</span>
        </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Form Input (Kiri) --}}
        <div class="lg:col-span-5">
            <div class="bg-white border border-slate-100 rounded-[3rem] p-8 shadow-sm sticky top-6">
                <h3 class="font-black text-slate-900 uppercase tracking-tighter text-lg mb-6">
                    Input Tugas <span class="text-indigo-500 italic" id="role-name-display">{{ $roles[0]->nama_role }}</span>
                </h3>

                <form action="{{ route('guru.proyek.roadmap.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="proyek_role_id" id="proyek_role_id" value="{{ $roles[0]->id }}">
                    <input type="hidden" name="proyek_id" value="{{ $proyek->id }}">

                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-1 space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Urutan</label>
                            <input type="number" name="urutan" id="input-urutan" value="{{ $roles[0]->roadmaps->count() + 1 }}" readonly
                                class="w-full px-4 py-4 bg-slate-100 border-none rounded-2xl font-black text-center text-sm outline-none cursor-not-allowed">
                        </div>
                        <div class="col-span-3 space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Nama Tugas</label>
                            <input type="text" name="judul_tugas" required placeholder="Contoh: Slicing UI ke HTML" class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Instruksi Pengerjaan</label>
                        <input id="instruksi" type="hidden" name="instruksi">
                        <trix-editor input="instruksi" placeholder="Tulis instruksi pengerjaan..."></trix-editor>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-rose-500 uppercase ml-1">Deadline Tugas</label>
                            <input type="date" name="deadline_tugas" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-2 focus:ring-rose-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-emerald-500 uppercase ml-1">Poin XP</label>
                            <input type="number" name="poin" placeholder="100" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-5 rounded-[2rem] font-black text-sm transition shadow-xl shadow-indigo-100 flex items-center justify-center gap-3 uppercase tracking-widest">
                        Simpan Tugas <i class="fas fa-plus-circle"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Timeline (Kanan) --}}
        <div class="lg:col-span-7 space-y-6">
            <h3 class="font-black text-slate-900 italic text-xl uppercase tracking-tighter px-4">
                Timeline Tugas: <span id="timeline-role-name" class="text-indigo-600">{{ $roles[0]->nama_role }}</span>
            </h3>

            @foreach($roles as $role)
            <div id="content-{{ Str::slug($role->nama_role) }}" class="role-content space-y-4 {{ $loop->first ? '' : 'hidden' }}">
                @forelse($role->roadmaps->sortBy('urutan') as $task)
                <div class="group bg-white border border-slate-100 rounded-[2.5rem] p-6 border-l-8 border-l-indigo-500 shadow-sm transition-all hover:shadow-md">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <span class="text-[9px] font-black text-slate-400 uppercase">Langkah #{{ $task->urutan }}</span>
                            <h4 class="font-black text-slate-900 text-lg uppercase leading-tight">{{ $task->judul_tugas }}</h4>
                            
                            <div class="flex flex-wrap items-center gap-3 mt-2">
                                <span class="text-[9px] font-black bg-rose-50 text-rose-500 px-3 py-1 rounded-full border border-rose-100 uppercase">
                                    <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($task->deadline_tugas)->format('d M Y') }}
                                </span>
                                <span class="text-[9px] font-black bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full border border-emerald-100 uppercase">
                                    <i class="fas fa-star mr-1"></i> {{ $task->poin }} XP
                                </span>
                            </div>

                            <div class="mt-4 text-slate-600 text-xs leading-relaxed instruction-content">
                                {!! $task->instruksi !!}
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" onclick="openEditModal({{ json_encode($task) }})"
                                class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-amber-50 hover:text-amber-500 transition flex items-center justify-center">
                                <i class="fas fa-edit text-xs"></i>
                            </button>

                            <form action="{{ route('guru.proyek.roadmap.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-full bg-slate-50 text-slate-300 hover:bg-rose-50 hover:text-rose-500 transition flex items-center justify-center">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="border-4 border-dashed border-slate-50 rounded-[3rem] p-12 text-center">
                    <p class="text-xs font-black text-slate-300 uppercase italic tracking-widest">Belum ada tugas untuk role ini.</p>
                </div>
                @endforelse
            </div>
            @endforeach

            {{-- Final Action --}}
            <div class="pt-10 flex flex-col items-center gap-6">
                <a href="{{ route('guru.proyek.dashboard') }}" class="bg-slate-900 hover:bg-emerald-600 text-white px-20 py-5 rounded-[2.5rem] font-black text-sm transition-all shadow-2xl flex items-center gap-4 group uppercase tracking-[0.1em]">
                    Selesaikan & Publish <i class="fas fa-rocket text-xs group-hover:animate-bounce"></i>
                </a>
                
                <div class="text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 text-center">Atau simpan sebagai draft dan</p>
                    <a href="{{ route('guru.proyek.dashboard') }}" class="text-slate-400 hover:text-rose-500 font-black text-[10px] uppercase tracking-[0.3em] transition-all underline decoration-slate-200 underline-offset-8">
                        Kembali ke Dashboard Utama
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 z-[99] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[3rem]">
            <div class="bg-slate-900 p-8 text-white flex justify-between items-center">
                <h3 class="text-xl font-black uppercase italic tracking-tighter">Edit <span class="text-indigo-400">Milestone</span></h3>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
            </div>
            <form id="editForm" method="POST" class="p-8 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-4 gap-4">
                    <div class="col-span-1 space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Urutan</label>
                        <input type="number" name="urutan" id="edit-urutan" class="w-full px-4 py-4 bg-slate-100 border-none rounded-2xl font-black text-center text-sm outline-none">
                    </div>
                    <div class="col-span-3 space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Nama Milestone</label>
                        <input type="text" name="judul_tugas" id="edit-judul" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold">
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Instruksi Pengerjaan</label>
                    {{-- Input hidden untuk menampung data --}}
                    <input id="edit-instruksi-hidden" type="hidden" name="instruksi">
                    {{-- Editor menunjuk ke ID input di atas --}}
                    <trix-editor id="edit-trix" input="edit-instruksi-hidden" placeholder="Tulis instruksi..."></trix-editor>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-rose-500 uppercase ml-1">Deadline</label>
                        <input type="date" name="deadline_tugas" id="edit-deadline" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-emerald-500 uppercase ml-1">XP</label>
                        <input type="number" name="poin" id="edit-poin" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold">
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-grow bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest">Update</button>
                    <button type="button" onclick="closeEditModal()" class="px-8 bg-slate-100 text-slate-500 py-4 rounded-2xl font-black text-xs uppercase tracking-widest">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. Trix Configuration (Heading 1 & Upload)
    document.addEventListener("trix-before-initialize", () => {
        Trix.config.blockAttributes.heading1 = {
            tagName: "h1",
            terminal: true,
            breakOnReturn: true,
            group: false
        };
    });

    // 2. Fungsi Switch Role (PENTING: Untuk ganti tab & update ID Role di form input)
    function switchRole(roleSlug, roleId) {
        // Update visual tombol tab
        document.querySelectorAll('.role-tab').forEach(btn => {
            btn.classList.remove('bg-indigo-500', 'text-white', 'shadow-xl');
            btn.classList.add('bg-slate-50', 'text-slate-400');
        });

        const activeBtn = document.getElementById('btn-' + roleSlug);
        if (activeBtn) {
            activeBtn.classList.add('bg-indigo-500', 'text-white', 'shadow-xl');
        }

        // UPDATE INPUT HIDDEN (Agar saat tambah tugas, masuk ke role yang benar)
        const roleInput = document.getElementById('proyek_role_id');
        if (roleInput) {
            roleInput.value = roleId;
        }

        // Update Text Display (Nama Role di Header Timeline)
        const roleName = activeBtn.querySelector('span').innerText;
        document.getElementById('role-name-display').innerText = roleName;
        document.getElementById('timeline-role-name').innerText = roleName;

        // Switch Content Timeline
        document.querySelectorAll('.role-content').forEach(content => {
            content.classList.add('hidden');
        });
        const targetContent = document.getElementById('content-' + roleSlug);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }
    }

    // 3. Fungsi Open Edit Modal
    function openEditModal(task) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        let url = "{{ route('guru.proyek.roadmap.update', ':id') }}";
        form.action = url.replace(':id', task.id); 

        document.getElementById('edit-urutan').value = task.urutan;
        document.getElementById('edit-judul').value = task.judul_tugas;
        document.getElementById('edit-deadline').value = task.deadline_tugas;
        document.getElementById('edit-poin').value = task.poin;
        
        const trixEditor = document.querySelector("#edit-trix");
        if (trixEditor && trixEditor.editor) {
            trixEditor.editor.loadHTML(task.instruksi || "");
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // 4. Attachment/Image Upload Logic
    (function() {
        var HOST = "{{ route('guru.proyek.trix.upload') }}"; 

        addEventListener("trix-attachment-add", function(event) {
            if (event.attachment.file) {
                uploadFileAttachment(event.attachment);
            }
        });

        function uploadFileAttachment(attachment) {
            var file = attachment.file;
            var form = new FormData;
            form.append("file", file); 
            form.append("_token", "{{ csrf_token() }}");

            var xhr = new XMLHttpRequest;
            xhr.open("POST", HOST, true);

            xhr.upload.onprogress = function(event) {
                var progress = event.loaded / event.total * 100;
                attachment.setUploadProgress(progress);
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    return attachment.setAttributes({
                        url: data.url,
                        href: data.url
                    });
                } else {
                    alert("Upload gagal!");
                    attachment.remove();
                }
            };
            xhr.send(form);
        }
    })();
</script>
@endsection