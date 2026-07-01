<!DOCTYPE html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-skin="default"
    data-assets-path="../../assets/" data-template="vertical-menu-template" data-bs-theme="light">

@include('Admin.Layouts.header')

<body>
    <!-- ✅ 1. jQuery dulu -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('Admin.Layouts.sidebar')

            <div class="layout-page">
                @include('Admin.Layouts.navbar')

                <div class="content-wrapper">
                    <div class="container-xxl grow container-p-y">
                        @include('Admin.Layouts.Parts.suspend')

                        @yield('content')

                        @include('Admin.Layouts.Parts.detail-notification')

                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
    </div>


    @include('Admin.Layouts.footer')

    @include('sweetalert::alert')

</body>

</html>
