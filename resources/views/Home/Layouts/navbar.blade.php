<!-- Navbar: Start -->
<nav class="layout-navbar shadow-none py-0">
    <div class="container">
        <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
            <!-- Menu logo wrapper: Start -->
            <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-8">
                <!-- Mobile menu toggle: Start-->
                <button class="navbar-toggler border-0 px-0 me-4" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="icon-base bx bx-menu icon-lg align-middle text-heading fw-medium"></i>
                </button>
                <!-- Mobile menu toggle: End-->
                <a href="landing-page.html" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="{{ asset('assets/img/favicon/saudivox.jpg') }}" alt="{{ config('app.name') }}"
                            style="height: 32px; width: 32px; object-fit: cover; border-radius: 50%;">
                    </span>

                    <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1 fst-italic fs-6 fs-xl-5"
                        id="app-name">{{ config('app.name') }}</span>
                </a>
            </div>
            <!-- Menu logo wrapper: End -->
            <!-- Menu wrapper: Start -->
            <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl p-2"
                    type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="icon-base bx bx-x icon-lg"></i>
                </button>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-medium" aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium" href="landing-page.html#landingContact">Contact us</a>
                    </li>
                    {{-- <li class="nav-item mega-dropdown">
                        <a href="javascript:void(0);"
                            class="nav-link dropdown-toggle navbar-ex-14-mega-dropdown mega-dropdown fw-medium"
                            aria-expanded="false" data-bs-toggle="mega-dropdown" data-trigger="hover">
                            <span data-i18n="Pages">Pages</span>
                        </a>
                        <div class="dropdown-menu p-4 p-xl-8">
                            <div class="row gy-4">
                                <div class="col-12 col-lg">
                                    <div class="h6 d-flex align-items-center mb-3 mb-lg-4">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-primary"><i
                                                    class="icon-base bx bx-grid-alt"></i></span>
                                        </div>
                                        <span class="ps-1">Other</span>
                                    </div>
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link" href="pricing-page.html">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                <span data-i18n="Pricing">Pricing</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link" href="payment-page.html">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                <span data-i18n="Payment">Payment</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link" href="checkout-page.html">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                <span data-i18n="Checkout">Checkout</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link" href="help-center-landing.html">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                <span data-i18n="Help Center">Help Center</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="h6 d-flex align-items-center mb-3 mb-lg-4">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-primary"><i
                                                    class="icon-base bx bx-lock-open icon-lg"></i></span>
                                        </div>
                                        <span class="ps-1">Auth Demo</span>
                                    </div>
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-login-basic.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Login (Basic)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-login-cover.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Login (Cover)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-register-basic.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Register (Basic)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-register-cover.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Register (Cover)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-register-multisteps.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Register (Multi-steps)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-forgot-password-basic.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Forgot Password (Basic)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-forgot-password-cover.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Forgot Password (Cover)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-reset-password-basic.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Reset Password (Basic)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-reset-password-cover.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Reset Password (Cover)
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="h6 d-flex align-items-center mb-3 mb-lg-4">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-primary"><i
                                                    class="icon-base bx bx-image-alt icon-lg"></i></span>
                                        </div>
                                        <span class="ps-1">Other</span>
                                    </div>
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/pages-misc-error.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Error
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/pages-misc-under-maintenance.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Under Maintenance
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/pages-misc-comingsoon.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Coming Soon
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/pages-misc-not-authorized.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Not Authorized
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-verify-email-basic.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Verify Email (Basic)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-verify-email-cover.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Verify Email (Cover)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-two-steps-basic.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Two Steps (Basic)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mega-dropdown-link"
                                                href="../vertical-menu-template/auth-two-steps-cover.html"
                                                target="_blank">
                                                <i class="icon-base bx bx-radio-circle me-1"></i>
                                                Two Steps (Cover)
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg-4 d-none d-lg-block">
                                    <div class="bg-body nav-img-col p-2">
                                        <img src="../../assets/img/front-pages/misc/nav-item-col-img.png"
                                            alt="nav item col image" class="w-100" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> --}}
                </ul>
            </div>
            <div class="landing-menu-overlay d-lg-none"></div>
            <!-- Menu wrapper: End -->
            <!-- Toolbar: Start -->
            <!-- Toolbar: Start -->
            <ul class="navbar-nav flex-row align-items-center ms-auto">

                <!-- Style Switcher -->
                <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);"
                        data-bs-toggle="dropdown">
                        <i class="icon-base bx bx-sun icon-lg theme-icon-active"></i>
                        <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                        <li>
                            <button type="button" class="dropdown-item align-items-center active"
                                data-bs-theme-value="light" aria-pressed="false">
                                <span><i class="icon-base bx bx-sun icon-md me-3" data-icon="sun"></i>Light</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark"
                                aria-pressed="true">
                                <span><i class="icon-base bx bx-moon icon-md me-3" data-icon="moon"></i>Dark</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system"
                                aria-pressed="false">
                                <span><i class="icon-base bx bx-desktop icon-md me-3"
                                        data-icon="desktop"></i>System</span>
                            </button>
                        </li>
                    </ul>
                </li>
                <!-- / Style Switcher -->

                {{-- Notification (hanya kalau login) --}}
                @auth
                    @include('Home.Layouts.notification')
                @endauth
                <!-- navbar button: Start -->
                <li>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <span class="tf-icons icon-base bx bx-grid-alt scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <span class="tf-icons icon-base bx bx-log-in-circle scaleX-n1-rtl me-md-1"></span>
                            <span class="d-none d-md-block">Login</span>
                        </a>
                    @endauth
                </li>
                <!-- navbar button: End -->

            </ul>

        </div>
    </div>
</nav>
<!-- Navbar: End -->
