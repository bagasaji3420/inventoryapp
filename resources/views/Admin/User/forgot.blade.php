<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Forgot Password</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">

    <!-- Form Validation CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}">

    <!-- JS (defer biar ga blocking) -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}" defer></script>
    <script src="{{ asset('assets/js/config.js') }}" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('sweetalert::alert')
</head>

<body>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">

                <div class="card px-sm-6 px-0">
                    <div class="card-body">

                        <h4 class="mb-1 text-center">Forgot Password?</h4>
                        <p class="mb-6 text-center">
                            Enter your email and we'll send you instructions to reset your password
                        </p>

                        {{-- SUCCESS --}}
                        @if (session('success'))
                            <div class="alert alert-success text-center">
                                {{ session('success') }}

                                <div class="mt-3">
                                    <a href="https://mail.google.com" target="_blank">
                                        Open Gmail
                                    </a>
                                </div>
                            </div>

                            {{-- ERROR --}}
                        @elseif (session('error'))
                            <div class="alert alert-warning text-center">
                                Email not registered.
                                <a href="{{ route('register') }}">Register</a>
                            </div>

                            {{-- FORM --}}
                        @else
                            <form id="formAuthentication" method="POST" action="{{ route('forgotPasswordLink') }}"
                                class="mb-6">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Enter your email" required autofocus>
                                </div>

                                <button id="submitBtn" type="submit" class="btn btn-primary w-100">
                                    Send Reset Link
                                </button>
                            </form>
                        @endif

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="d-flex justify-content-center">
                                <i class="bx bx-chevron-left me-1"></i>
                                Back to login
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- JS --}}
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}" defer></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}" defer></script>

    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}" defer></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}" defer></script>

    <script src="{{ asset('assets/js/main.js') }}" defer></script>

    <script>
        document.getElementById('formAuthentication')?.addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
        });
    </script>

</body>

</html>
