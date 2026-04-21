<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

<style>
    /* 1. Base Editor Styling */
    trix-editor {
        min-height: 450px !important;
        padding: 2rem !important;
        background-color: transparent !important;
        border: none !important;
        color: #475569;
        font-weight: 500;
        outline: none !important;
        line-height: 1.6;
        display: block !important;
    }
    trix-editor h1 {
        font-size: 1.875rem !important;
        font-weight: 700 !important;
        line-height: 1.2 !important;
        color: #1e293b !important;
        display: block !important;
    }
    /* 2. List Styling */
    trix-editor ul { list-style-type: disc !important; margin-left: 1.5rem !important; }
    trix-editor ol { list-style-type: decimal !important; margin-left: 1.5rem !important; }
    trix-editor li { display: list-item !important; margin-bottom: 0.25rem !important; }

    /* 4. Toolbar & Dropdown Styling */
    trix-toolbar { 
        border-bottom: 1px solid #f1f5f9 !important; 
        padding: 15px 20px !important; 
        background: white !important;
        position: sticky; top: 0; z-index: 50;
        border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem;
    }

    .trix-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 800;
        color: #475569;
        margin-right: 5px;
        background: #f8fafc;
        cursor: pointer;
        outline: none;
    }

    trix-toolbar .trix-button--active,
    trix-toolbar .trix-button.trix-active { 
        color: #2563eb !important; 
        background: #eff6ff !important; 
        border-radius: 8px !important; 
    }

    /* CodeMirror */
    .CodeMirror { height: 250px; font-family: 'Fira Code', monospace; font-size: 13px; border-radius: 1rem; }
    .trix-content {
        display: flex;
        flex-wrap: wrap; /* Supaya jika layar kecil, gambar turun ke bawah */
        gap: 20px;       /* Jarak antar gambar */
        justify-content: center;
    }
    trix-editor, .trix-content {
        display: block; /* Default untuk teks */
    }

    /* Targetkan area yang berisi rangkaian attachment (gambar) */
    trix-editor, .trix-content {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 15px !important;
    }

    /* Mengatur gambar (figure) agar otomatis menjadi kolom */
    trix-editor figure.attachment {
        display: inline-block !important; /* Paksa berjajar ke samping */
        vertical-align: top;
        margin: 0 5px 10px 0 !important;
        /* Untuk 2 kolom pakai 48%, untuk 3 kolom pakai 31% */
        width: 31% !important; 
        max-width: 31% !important;
    }

    /* Memastikan gambar memenuhi ruang kolomnya */
    trix-editor figure.attachment img {
        width: 100% !important;
        height: auto !important;
    }

    /* Menghilangkan metadata (nama file/ukuran) agar tidak merusak layout */
    trix-editor .attachment__metadata {
        display: none !important;
    }

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

<script>
    // --- 1. REGISTRASI ATRIBUT ---
    document.addEventListener("trix-before-initialize", () => {
        const { textAttributes } = Trix.config;
        Trix.config.blockAttributes.heading1 = {
            tagName: "h1",
            terminal: true,
            breakOnReturn: true,
            group: false
        };

        textAttributes.inlineStyle = { tagName: "span", inheritable: true };

    });


    function applyTrixAttribute(select, type) {
        const toolbar = select.closest('trix-toolbar');
        const editor = toolbar.nextElementSibling.editor;
        const value = select.value;

        const allAttributes = Array.from(select.options).map(opt => opt.value).filter(val => val);
        
        editor.setSelectedRange(editor.getSelectedRange()); 
        allAttributes.forEach(attr => editor.deactivateAttribute(attr));
        
        if (value) editor.activateAttribute(value);
        select.selectedIndex = 0; 
    }


    document.addEventListener('DOMContentLoaded', function() {

        const codeElement = document.getElementById("editor-final");
        if (codeElement) {
            window.editorGuru = CodeMirror.fromTextArea(codeElement, {
                lineNumbers: true, theme: "dracula", mode: "xml", lineWrapping: true
            });
        }

  
        addEventListener("trix-attachment-add", async (event) => {
            if (!event.attachment.file) return;

            const formData = new FormData();
            formData.append("image", event.attachment.file);

            try {
                const response = await fetch('{{ route("guru.submateri.uploadImage") }}', {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    // KUNCI UTAMA: Update atribut Trix dengan URL permanen dari controller
                    event.attachment.setAttributes({
                        url: data.url,
                        href: data.url
                    });
                } else {
                    event.attachment.remove();
                    alert("Upload gagal di server");
                }
            } catch (e) {
                event.attachment.remove();
                alert("Kesalahan koneksi");
            }
        });
    });
</script>