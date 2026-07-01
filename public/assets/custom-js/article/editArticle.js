Dropzone.autoDiscover = false;

new Dropzone("#featured-dropzone", {
    url: "#",
    autoProcessQueue: false,
    maxFiles: 1,
    acceptedFiles: "image/*",
    addRemoveLinks: true,

    init: function () {
        this.on("addedfile", function (file) {
            let dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById("real_image").files = dt.files;
        });

        this.on("removedfile", function () {
            document.getElementById("real_image").value = "";
        });
    },
});

document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("is_breaking");
    const wrapper = document.getElementById("breaking_until_wrapper");

    function toggleBreaking() {
        if (toggle.checked) {
            wrapper.style.display = "block";
        } else {
            wrapper.style.display = "none";
        }
    }

    // initial state
    toggleBreaking();

    // on change
    toggle.addEventListener("change", toggleBreaking);
});

tinymce.init({
    selector: "#editor",
    height: 500,

    plugins: "lists link image table code preview",
    toolbar: `
                undo redo | 
                formatselect | 
                bold italic underline | 
                alignleft aligncenter alignright | 
                bullist numlist | 
                link image table | 
                code preview
            `,

    // 🔥 INI FIX UTAMA (GANTI HANDLER)
    images_upload_url: "{{ route('articles.editor.upload') }}",
    automatic_uploads: true,

    images_upload_credentials: true,

    // kirim CSRF
    setup: function (editor) {
        editor.on("BeforeUpload", function (e) {
            e.formData.append("_token", "{{ csrf_token() }}");
        });
    },

    menubar: false,
    relative_urls: false,
    remove_script_host: false,
    convert_urls: true,
});
