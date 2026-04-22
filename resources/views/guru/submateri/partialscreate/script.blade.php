<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

<style>
    /* 1. Base Editor Styling */
    trix-editor, .trix-content {
        min-height: 450px !important;
        padding: 2rem !important;
        background-color: white !important;
        color: #334155;
        line-height: 1.6;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0 0 1.5rem 1.5rem;
        outline: none !important;
    }

    /* 2. Heading & Text Styling */
    trix-editor h1, .trix-content h1 {
        font-size: 1.875rem !important;
        font-weight: 700 !important;
        margin-bottom: 1rem !important;
        color: #1e293b !important;
        display: block !important;
        clear: both;
    }

    trix-editor p, .trix-content p {
        margin-bottom: 1rem !important;
        clear: both;
    }

    /* 3. List Styling */
    trix-editor ul, .trix-content ul { 
        list-style-type: disc !important; 
        margin-left: 1.5rem !important; 
        margin-bottom: 1rem !important;
        display: block !important;
    }
    trix-editor ol, .trix-content ol { 
        list-style-type: decimal !important; 
        margin-left: 1.5rem !important; 
        margin-bottom: 1rem !important;
        display: block !important;
    }
    trix-editor li, .trix-content li { 
        display: list-item !important; 
        margin-bottom: 0.25rem !important; 
    }

    /* 4. Image Grid Styling (Dynamic 1 or 2 Grid) */
    
    /* State Default: 1 Gambar (Full Width) */
    trix-editor figure.attachment, 
    .trix-content figure.attachment {
        display: block !important;
        margin: 0 auto 20px auto !important;
        width: 100% !important;
        max-width: 100% !important;
        transition: all 0.3s ease;
    }

    /* State: Jika terdeteksi minimal ada 2 gambar (Grid 2 Kolom) */
    /* Menggunakan nth-of-type(2) agar lebih akurat mendeteksi jumlah gambar */
    trix-editor:has(figure.attachment:nth-of-type(2)) figure.attachment,
    .trix-content:has(figure.attachment:nth-of-type(2)) figure.attachment {
        display: inline-block !important;
        vertical-align: top;
        width: 49% !important; /* Lebar disesuaikan agar pas 2 kolom */
        max-width: 49% !important;
        margin: 0 0.5% 15px 0.5% !important;
    }

    /* Styling Gambar didalamnya */
    trix-editor figure.attachment img, 
    .trix-content figure.attachment img {
        width: 100% !important;
        height: auto !important;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        object-fit: cover;
    }

    /* Menghilangkan Metadata */
    trix-editor .attachment__metadata {
        display: none !important;
    }

    /* Caption Styling */
    trix-editor figcaption, .trix-content figcaption {
        text-align: center !important;
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 5px;
    }

    /* 5. Toolbar Styling */
    trix-toolbar { 
        border: 1px solid #e2e8f0 !important;
        border-bottom: none !important;
        padding: 15px 20px !important; 
        background: #f8fafc !important;
        border-top-left-radius: 1.5rem; 
        border-top-right-radius: 1.5rem;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    trix-toolbar .trix-button--active {
        background: #eff6ff !important;
        color: #2563eb !important;
    }

    /* 6. Responsif untuk HP */
    @media (max-width: 600px) {
        trix-editor figure.attachment,
        trix-editor:has(figure.attachment:nth-of-type(2)) figure.attachment {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 0 15px 0 !important;
            display: block !important;
        }
    }
</style>

<script>
    document.addEventListener("trix-before-initialize", () => {
        // Konfigurasi tambahan jika diperlukan
        Trix.config.blockAttributes.heading1 = {
            tagName: "h1",
            terminal: true,
            breakOnReturn: true,
            group: false
        };
    });

    // Otomatisasi: Menghapus baris kosong yang mengganggu flex layout
    document.addEventListener("trix-change", function(event) {
        // Logika opsional jika Anda ingin membersihkan elemen kosong
    });
</script>

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