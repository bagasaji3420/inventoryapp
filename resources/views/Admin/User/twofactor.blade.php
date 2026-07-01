<!doctype html>

<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-assets-path="../../assets/"
    data-template="vertical-menu-template" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Verification</title>

    <meta name="description" content="" />

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
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!--  Two Steps Verification -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <!-- /Logo -->
                        <h4 class="mb-1">Two Step Verification 📧</h4>
                        <p class="text-start mb-6">
                            We sent a verification code to your gmail. Enter the code from the Gmail in the field
                            below. <a href="https://mail.google.com">Go to gmail</a>
                            <span class="fw-medium d-block mt-1 text-heading"></span>
                        </p>
                        <p class="mb-0">Type your 6 digit security code</p>
                        <form id="twoStepsForm" action="{{ route('2fa.verify-otp') }}" method="POST">
                            @csrf
                            <div class="mb-6 form-control-validation">
                                <div
                                    class="auth-input-wrapper d-flex align-items-center justify-content-between numeral-mask-wrapper">
                                    <input type="tel"
                                        class="form-control otp-input auth-input h-px-50 text-center numeral-mask mx-sm-1 mt-2"
                                        maxlength="1" autofocus />
                                    <input type="tel"
                                        class="form-control otp-input auth-input h-px-50 text-center numeral-mask mx-sm-1 mt-2"
                                        maxlength="1" />
                                    <input type="tel"
                                        class="form-control otp-input auth-input h-px-50 text-center numeral-mask mx-sm-1 mt-2"
                                        maxlength="1" />
                                    <input type="tel"
                                        class="form-control otp-input auth-input h-px-50 text-center numeral-mask mx-sm-1 mt-2"
                                        maxlength="1" />
                                    <input type="tel"
                                        class="form-control otp-input auth-input h-px-50 text-center numeral-mask mx-sm-1 mt-2"
                                        maxlength="1" />
                                    <input type="tel"
                                        class="form-control otp-input auth-input h-px-50 text-center numeral-mask mx-sm-1 mt-2"
                                        maxlength="1" />

                                    <input type="hidden" name="otp" id="otp" />
                                </div>
                                <!-- Create a hidden field which is combined by 3 fields above -->
                            </div>
                            <button class="btn btn-primary d-grid w-100 mb-6">Verify my account</button>
                        </form>


                        <form action="{{ route('2fa.resend') }}" method="POST" id="resendForm">
                            @csrf
                        </form>

                        <div class="text-center">
                            Didn't get the code?
                            <a href="#" id="resendLink">Resend</a>
                        </div>
                    </div>
                </div>
                <!-- / Two Steps Verification -->
            </div>
        </div>
    </div>
    <script id="otp-script">
        const inputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('otp');
        const form = document.getElementById('twoStepsForm');

        inputs.forEach((input, index) => {

            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^0-9]/g, '');

                if (input.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                updateOTP();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // 🔥 HANDLE PASTE DI SINI
            input.addEventListener('paste', (e) => {
                e.preventDefault();

                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                const digits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);

                digits.split('').forEach((digit, i) => {
                    if (inputs[i]) {
                        inputs[i].value = digit;
                    }
                });

                updateOTP();

                // auto focus ke terakhir
                inputs[Math.min(digits.length, 5)].focus();

                // 🚀 auto submit kalau sudah 6 digit
                if (digits.length === 6) {
                    form.submit();
                }
            });
        });

        function updateOTP() {
            let otp = '';
            inputs.forEach(input => {
                otp += input.value;
            });

            hiddenInput.value = otp;

            // 🚀 auto submit kalau lengkap
            if (otp.length === 6) {
                form.submit();
            }
        }

        document.getElementById('resendLink').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('resendForm').submit();
        });

        document.addEventListener('DOMContentLoaded', function() {

            const resendLink = document.getElementById('resendLink');
            const resendForm = document.getElementById('resendForm');

            let timeLeft = 60;
            resendLink.style.pointerEvents = 'none';
            resendLink.style.opacity = '0.5';

            let timer = setInterval(() => {
                resendLink.textContent = `Resend in ${timeLeft}s`;
                timeLeft--;

                if (timeLeft < 0) {
                    clearInterval(timer);
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.style.opacity = '1';
                    resendLink.textContent = 'Resend';
                }
            }, 1000);

            resendLink.addEventListener('click', function(e) {
                e.preventDefault();
                resendForm.submit();
            });

        });
    </script>

    @include('sweetalert::alert')
    <!-- / Content -->



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->
    {{-- JS --}}
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}" defer></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}" defer></script>

    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}" defer></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}" defer></script>

    <script src="{{ asset('assets/js/main.js') }}" defer></script>

    <!-- Page JS -->
    <script src="../../assets/js/pages-auth.js"></script>
    <script src="../../assets/js/pages-auth-two-steps.js"></script>
</body>

</html>
