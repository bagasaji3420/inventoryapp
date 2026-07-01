<!DOCTYPE html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-skin="default"
    data-assets-path="../../assets/" data-template="vertical-menu-template" data-bs-theme="light">

@include('Home.Layouts.header')

<body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../../assets/vendor/js/dropdown-hover.js"></script>
    <script src="../../assets/vendor/js/mega-dropdown.js"></script>

    @include('Home.Layouts.navbar')

    <div>
        <section>
            @yield('content')
        </section>
    </div>


    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
    </div>


    @include('Home.Layouts.footer')

    @include('sweetalert::alert')

</body>

</html>
