<!-- Footer: Start -->
<footer class="landing-footer bg-body footer-text">
    <div class="footer-top position-relative overflow-hidden z-1">
        <img src="{{ asset('assets/img/front-pages/backgrounds/footer-bg.png') }}"
             alt="footer bg" class="footer-bg banner-bg-img z-n1" />
        <div class="container">
            <div class="row gx-0 gy-6 g-lg-10">

                {{-- Brand & Newsletter --}}
                <div class="col-lg-5">
                    <a href="{{ url('/') }}" class="app-brand-link mb-6">
                        <span class="app-brand-text demo text-white fw-bold ms-2 ps-1">
                            {{ config('app.name') }}
                        </span>
                    </a>
                    <p class="footer-text footer-logo-description mb-6">
                        Portal berita dan informasi terkini seputar komunitas pelajar Indonesia di Arab Saudi.
                    </p>
                    <form class="footer-form">
                        <label for="footer-email" class="small">Langganan Newsletter</label>
                        <div class="d-flex mt-1">
                            <input type="email"
                                   class="form-control rounded-0 rounded-start-bottom rounded-start-top"
                                   id="footer-email" placeholder="Email kamu" />
                            <button type="submit"
                                    class="btn btn-primary shadow-none rounded-0 rounded-end-bottom rounded-end-top">
                                Langganan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Navigasi --}}
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <h6 class="footer-title mb-6">Navigasi</h6>
                    <ul class="list-unstyled">
                        <li class="mb-4">
                            <a href="{{ url('/') }}" class="footer-link">Beranda</a>
                        </li>
                        <li class="mb-4">
                            <a href="#landingContact" class="footer-link">Kontak</a>
                        </li>
                    </ul>
                </div>

                {{-- Akun --}}
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <h6 class="footer-title mb-6">Akun</h6>
                    <ul class="list-unstyled">
                        @auth
                        <li class="mb-4">
                            <a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a>
                        </li>
                        @else
                        <li class="mb-4">
                            <a href="{{ route('login') }}" class="footer-link">Login</a>
                        </li>
                        <li class="mb-4">
                            <a href="{{ route('registration') }}" class="footer-link">Daftar</a>
                        </li>
                        @endauth
                    </ul>
                </div>

                {{-- Sosmed --}}
                <div class="col-lg-3 col-md-4">
                    <h6 class="footer-title mb-6">Ikuti Kami</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white" target="_blank" title="Instagram">
                            <i class="bx bxl-instagram fs-4"></i>
                        </a>
                        <a href="#" class="text-white" target="_blank" title="Twitter/X">
                            <i class="bx bxl-twitter fs-4"></i>
                        </a>
                        <a href="#" class="text-white" target="_blank" title="Facebook">
                            <i class="bx bxl-facebook fs-4"></i>
                        </a>
                        <a href="#" class="text-white" target="_blank" title="YouTube">
                            <i class="bx bxl-youtube fs-4"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom py-3 py-md-5">
        <div class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
            <div class="mb-2 mb-md-0">
                <span class="footer-bottom-text">© {{ date('Y') }}</span>
                <a href="{{ url('/') }}" class="text-white">{{ config('app.name') }},</a>
                <span class="footer-bottom-text"> All rights reserved.</span>
            </div>
        </div>
    </div>
</footer>
<!-- Footer: End -->

<!-- Core JS -->
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/nouislider/nouislider.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('assets/js/front-main.js') }}"></script>
<script src="{{ asset('assets/js/front-page-landing.js') }}"></script>