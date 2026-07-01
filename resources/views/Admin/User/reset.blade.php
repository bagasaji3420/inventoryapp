<!doctype html>

<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-assets-path="../../assets/"
    data-template="vertical-menu-template" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Reset Password</title>

    <meta name="description" content="" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">

    <!-- Page CSS (Auth) -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">

    <!-- Form Validation -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}">

    <!-- Helpers (WAJIB sebelum config) -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!-- Config -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @include('sweetalert::alert')
</head>

<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Reset Password -->
                <div class="card px-sm-6">
                    <div class="card-body">
                        <h4 class="mb-1 text-center">Reset Password </h4>
                        <p class="mb-6">
                            <span class="fw-medium">Your new password must be different from previously used
                                passwords</span>
                        </p>
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf

                            <!-- TOKEN -->
                            <input type="hidden" name="token" value="{{ $token }}"
                                value="{{ old('token', $token) }}">

                            <!-- EMAIL -->
                            <input type="hidden" name="email" value="{{ $email }}"
                                value="{{ old('email', $email) }}">

                            <!-- PASSWORD -->
                            <div class="mb-6 form-password-toggle form-control-validation">
                                <label class="form-label">New Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" required>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- CONFIRM PASSWORD -->
                            <div class="mb-6 form-password-toggle form-control-validation">
                                <label class="form-label">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" required>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @if ($errors->has('email'))
                                <div class="alert alert-danger text-center">
                                    {{ $errors->first('email') }}

                                    <div class="mt-3">
                                        <a href="{{ route('forgotPassword') }}" class="btn btn-sm btn-primary">
                                            Request new link
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary d-grid w-100 mb-6">
                                Set new password
                            </button>

                            <div class="text-center">
                                <a href="{{ route('login') }}">
                                    Back to login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Reset Password -->
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <!-- Form Validation -->
    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>

    <!-- Main JS (WAJIB buat toggle password) -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page Auth -->
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
</body>

</html>
