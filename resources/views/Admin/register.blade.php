<!doctype html>

<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-assets-path="../../assets/"
    data-template="vertical-menu-template" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Register</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="../../assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="../../assets/vendor/libs/pickr/pickr-themes.css" />

    <link rel="stylesheet" href="../../assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->

    <!-- Vendor -->
    <link rel="stylesheet" href="../../assets/vendor/libs/bs-stepper/bs-stepper.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet"
        href="../../assets/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->

    <!-- Page -->
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="../../assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <a href="/" class="app-brand auth-cover-brand gap-2">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <defs>
                            <path
                                d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                id="path-1"></path>
                            <path
                                d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                id="path-3"></path>
                            <path
                                d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                                id="path-4"></path>
                            <path
                                d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                                id="path-5"></path>
                        </defs>
                        <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                <g id="Icon" transform="translate(27.000000, 15.000000)">
                                    <g id="Mask" transform="translate(0.000000, 8.000000)">
                                        <mask id="mask-2" fill="white">
                                            <use xlink:href="#path-1"></use>
                                        </mask>
                                        <use fill="currentColor" xlink:href="#path-1"></use>
                                        <g id="Path-3" mask="url(#mask-2)">
                                            <use fill="currentColor" xlink:href="#path-3"></use>
                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                        </g>
                                        <g id="Path-4" mask="url(#mask-2)">
                                            <use fill="currentColor" xlink:href="#path-4"></use>
                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                        </g>
                                    </g>
                                    <g id="Triangle"
                                        transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                        <use fill="currentColor" xlink:href="#path-5"></use>
                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
            </span>
            <span class="app-brand-text demo text-heading fw-bold">Registration</span>
        </a>
        <!-- /Logo -->
        <div class="authentication-inner row m-0">
            <!-- Left Text -->
            <div class="d-none d-lg-flex col-lg-4 align-items-center justify-content-end p-5 pe-0">
                <div class="w-px-400">
                    <img src="../../assets/img/illustrations/create-account-light.png" class="img-fluid"
                        alt="multi-steps" width="600" data-app-dark-img="illustrations/create-account-dark.png"
                        data-app-light-img="illustrations/create-account-light.png" />
                </div>
            </div>
            <!-- /Left Text -->

            <!--  Multi Steps Registration -->
            <div class="d-flex col-lg-8 align-items-center justify-content-center authentication-bg p-5">
                <div class="w-px-700">
                    <div id="multiStepsValidation" class="bs-stepper border-none shadow-none mt-5">
                        <div class="bs-stepper-header border-none pt-12 px-0">
                            <div class="step" data-target="#accountDetailsValidation">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle"><i class="icon-base bx bx-home"></i></span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Account</span>
                                        <span class="bs-stepper-subtitle">Account Details</span>
                                    </span>
                                </button>
                            </div>
                            <div class="line">
                                <i class="icon-base bx bx-chevron-right icon-22px"></i>
                            </div>
                            <div class="step" data-target="#personalInfoValidation">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-circle"><i class="icon-base bx bx-user"></i></span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Personal</span>
                                        <span class="bs-stepper-subtitle">Enter Information</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content px-0">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li> @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form id="multiStepsForm"
        method="POST" action="{{ route('register.store') }}">
    @csrf

    <!-- Account Details -->
    <div id="accountDetailsValidation" class="content">
        <div class="content-header mb-6">
            <h4 class="mb-0">Account Information</h4>
            <p class="mb-0">Enter Your Account Details</p>
        </div>

        <div class="row g-6">

            <!-- Username -->
            <div class="col-sm-6 form-control-validation">
                <label class="form-label">Username</label>
                <input type="text" name="multiStepsUsername" min="6" class="form-control"
                    value="{{ old('multiStepsUsername') }}">
                @error('multiStepsUsername')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Email -->
            <div class="col-sm-6 form-control-validation">
                <label class="form-label">Email</label>
                <input type="email" name="multiStepsEmail" class="form-control" value="{{ old('multiStepsEmail') }}">
                @error('multiStepsEmail')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Password -->
            <div class="col-sm-6 form-password-toggle form-control-validation">
                <label class="form-label">Password</label>
                <input type="password" name="multiStepsPass" class="form-control">
                @error('multiStepsPass')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="col-sm-6 form-password-toggle form-control-validation">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="multiStepsConfirmPass" class="form-control">
                @error('multiStepsConfirmPass')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-12 d-flex justify-content-between">
                <button class="btn btn-label-secondary btn-prev" disabled>Previous</button>
                <button class="btn btn-primary btn-next">Next</button>
            </div>

        </div>
    </div>

    <!-- Personal Info -->
    <div id="personalInfoValidation" class="content">
        <div class="content-header mb-6">
            <h4 class="mb-0">Personal Information</h4>
        </div>

        <div class="row g-6">

            <!-- First Name -->
            <div class="col-sm-6 form-control-validation">
                <label class="form-label">First Name</label>
                <input type="text" name="multiStepsFirstName" class="form-control"
                    value="{{ old('multiStepsFirstName') }}">
                @error('multiStepsFirstName')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Last Name -->
            <div class="col-sm-6">
                <label class="form-label">Last Name</label>
                <input type="text" name="multiStepsLastName" class="form-control"
                    value="{{ old('multiStepsLastName') }}">
            </div>

            <!-- Birth Date -->
            <div class="col-sm-4">
                <label class="form-label">Birth Date</label>
                <input type="date" name="multiStepsBirthDate" class="form-control"
                    value="{{ old('multiStepsBirthDate') }}">
                @error('multiStepsBirthDate')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Gender -->
            <div class="col-sm-4">
                <label class="form-label">Gender</label>
                <select name="multiStepsGender" class="form-select">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('multiStepsGender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('multiStepsGender') == 'female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('multiStepsGender')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Mobile -->
            <div class="col-sm-4">
                <label class="form-label">Mobile</label>
                <input type="text" name="multiStepsMobile" class="form-control"
                    value="{{ old('multiStepsMobile') }}">
                @error('multiStepsMobile')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Address -->
            <div class="col-md-12 form-control-validation">
                <label class="form-label">Address</label>
                <input type="text" name="multiStepsAddress" class="form-control"
                    value="{{ old('multiStepsAddress') }}">
                @error('multiStepsAddress')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- City -->
            <div class="col-sm-4">
                <label class="form-label">City</label>
                <input type="text" name="multiStepsCity" class="form-control"
                    value="{{ old('multiStepsCity') }}">
            </div>

            <!-- State -->
            <div class="col-sm-4">
                <label class="form-label">Country</label>
                <select id="multiStepsState" class="select2 form-select" name="multiStepsState">
                    <option value="">Select Country</option>
                </select>
            </div>

            <!-- Pincode -->
            <div class="col-sm-4">
                <label class="form-label">Pincode</label>
                <input type="text" name="multiStepsPincode" class="form-control"
                    value="{{ old('multiStepsPincode') }}">
            </div>

            <div class="col-12 d-flex justify-content-between">
                <button type="button" class="btn btn-label-secondary btn-prev">Previous</button>

                <!-- 🔥 FIX DISINI -->
                <button type="submit" class="btn btn-success btn-submit">
                    Submit
                </button>
            </div>

        </div>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    <!-- / Multi Steps Registration -->
    </div>
    </div>

    <script>
        // Check selected custom option
        window.Helpers.initCustomOptionCheck();
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const select = document.getElementById("multiStepsState");

            fetch("https://restcountries.com/v3.1/all?fields=name")
                .then(response => response.json())
                .then(data => {
                    // sort A-Z
                    data.sort((a, b) => a.name.common.localeCompare(b.name.common));

                    // isi option
                    data.forEach(country => {
                        const option = document.createElement("option");
                        option.value = country.name.common;
                        option.textContent = country.name.common;
                        select.appendChild(option);
                    });

                    // init select2
                    $('#multiStepsState').wrap('<div class="position-relative"></div>');
                    $('#multiStepsState').select2({
                        placeholder: "Select Country",
                        allowClear: true,
                        dropdownParent: $('#multiStepsState').parent()
                    });
                })
                .catch(error => {
                    console.error("Error fetch countries:", error);
                });
        });
    </script>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js  -->

    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>

    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/@algolia/autocomplete-js.js"></script>

    <script src="../../assets/vendor/libs/pickr/pickr.js"></script>

    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../../assets/vendor/libs/hammer/hammer.js"></script>

    <script src="../../assets/vendor/libs/i18n/i18n.js"></script>

    <script src="../../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../../assets/vendor/libs/cleave-zen/cleave-zen.js"></script>
    <script src="../../assets/vendor/libs/bs-stepper/bs-stepper.js"></script>
    <script src="../../assets/vendor/libs/select2/select2.js"></script>
    <script src="../../assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="../../assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="../../assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->

    <script src="../../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../../assets/js/pages-auth-multisteps.js"></script>
    </body>

</html>
