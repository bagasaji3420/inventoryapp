<!-- Core JS -->
<!-- build:js assets/vendor/js/theme.js  -->


@if (View::hasSection('livewire'))
    @livewireScripts
@endif


@vite(['resources/js/app.js'])


<script>
    document.querySelectorAll('img').forEach(img => {
        if (img.complete) {
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', () => {
                img.classList.add('loaded');
            });
        }
    });
</script>

<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}" defer></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}" defer></script>
<script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}" defer></script>

<script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}" defer></script>

<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}" defer></script>

<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}" defer></script>

<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}" defer></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}" defer></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>

<!-- Main JS -->

<script src="{{ asset('assets/js/main.js') }}" defer></script>



<script src="{{ asset('assets/js/forms-file-upload.js') }}"></script>
