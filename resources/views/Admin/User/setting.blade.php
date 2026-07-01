@extends('Admin.Layouts.app')
@section('content')
    <div class="col-md-12">
        <div class="nav-align-top">
            @include('Admin.User.menu')
        </div>

        <form action="{{ route('account.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card mb-6">
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                        <img src="{{ $user->avatar_url }}" alt="user-avatar"
                            onerror="this.onerror=null; this.src='{{ asset('assets/img/avatars/21.jpg') }}';"
                            class="d-block w-px-100 h-px-100 rounded object-fit-cover img-skeleton" id="uploadedAvatar"
                            loading="lazy" />
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload"
                                    class="account-file-input @error('avatar') is-invalid @enderror" hidden
                                    accept="image/png, image/jpeg" name="avatar" />
                            </label>
                            <button type="button" class="btn btn-label-secondary account-image-reset mb-4">
                                <i class="icon-base bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>
                            @error('avatar')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-6">

                        <!-- First Name -->
                        <div class="col-sm-4 form-control-validation">
                            <label class="form-label">First Name</label>
                            <input type="text" name="FirstName"
                                class="form-control @error('FirstName') is-invalid @enderror"
                                value="{{ old('FirstName', $user->first_name ?? '') }}">
                            @error('FirstName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="col-sm-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="LastName"
                                class="form-control @error('LastName') is-invalid @enderror"
                                value="{{ old('LastName', $user->last_name ?? '') }}">
                            @error('LastName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email (readonly) -->
                        <div class="col-sm-4">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                        </div>

                        <!-- Birth Date -->
                        <div class="col-sm-4 form-control-validation">
                            <label class="form-label">Birth Date</label>
                            <input type="date" name="BirthDate"
                                class="form-control @error('BirthDate') is-invalid @enderror"
                                value="{{ old('BirthDate', $user->profile->birth_date ?? '') }}">
                            @error('BirthDate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="col-sm-4 form-control-validation">
                            <label class="form-label">Gender</label>
                            <select name="Gender"
                                class="form-select @error('Gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male"
                                    {{ old('Gender', $user->profile->gender ?? '') == 'male' ? 'selected' : '' }}>
                                    Male
                                </option>
                                <option value="female"
                                    {{ old('Gender', $user->profile->gender ?? '') == 'female' ? 'selected' : '' }}>
                                    Female
                                </option>
                            </select>
                            @error('Gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="col-sm-4">
                            <label class="form-label">Username</label>
                            <input type="text" name="Username"
                                class="form-control @error('Username') is-invalid @enderror"
                                value="{{ old('Username', $user->username) }}">
                            @error('Username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mobile -->
                        <div class="col-sm-6">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="Mobile"
                                class="form-control @error('Mobile') is-invalid @enderror"
                                value="{{ old('Mobile', $user->profile->mobile ?? '') }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('Mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-md-6 form-control-validation">
                            <label class="form-label">Address</label>
                            <input type="text" name="Address"
                                class="form-control @error('Address') is-invalid @enderror"
                                value="{{ old('Address', $user->profile->address ?? '') }}">
                            @error('Address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="col-sm-4 form-control-validation">
                            <label class="form-label">City</label>
                            <input type="text" name="City"
                                class="form-control @error('City') is-invalid @enderror"
                                value="{{ old('City', $user->profile->city ?? '') }}">
                            @error('City')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="col-sm-4 form-control-validation">
                            <label class="form-label">Country</label>
                            <select id="State" class="select2 form-select" name="State"
                                data-allow-clear="true">

                                <!-- current -->
                                @if ($user->profile && $user->profile->country)
                                    <option selected value="{{ $user->profile->country }}">
                                        {{ $user->profile->country }}
                                    </option>
                                @endif

                                <option value="">Select Country</option>
                            </select>

                            @error('State')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pincode -->
                        <div class="col-sm-4">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="Pincode"
                                class="form-control @error('Pincode') is-invalid @enderror"
                                value="{{ old('Pincode', $user->profile->pincode ?? '') }}" maxlength="6">
                            @error('Pincode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3">Save changes</button>
                        <button type="reset" class="btn btn-label-secondary">Cancel</button>
                    </div>
                </div>
        </form>
    </div>
    <!-- /Account -->
    </div>
    <div class="card">
        <h5 class="card-header">Delete Account</h5>
        <div class="card-body">
            <div class="mb-6 col-12 ">
                <div class="alert alert-warning">
                    <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                    <p class="mb-0">Once you delete your account, there is no going back. Please be certain.
                    </p>
                </div>
            </div>
            <form id="formAccountDeactivation" method="post" action="{{ route('account.delete') }}">
                @csrf
                @method('DELETE')
                <div class="form-check my-8 ms-2">
                    <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
                    <label class="form-check-label" for="accountActivation">I confirm my account
                        deletion</label>
                </div>
                <button type="submit" class="btn btn-danger deactivate-account" disabled>
                    Delete Account
                </button>
            </form>
        </div>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const select = document.getElementById("State");

            fetch("https://restcountries.com/v3.1/all?fields=name,flags")
                .then(response => response.json())
                .then(data => {
                    data.sort((a, b) => a.name.common.localeCompare(b.name.common));

                    data.forEach(country => {
                        const option = document.createElement("option");
                        option.value = country.name.common;
                        option.textContent = country.name.common;
                        select.appendChild(option);
                    });

                    // Wrap dulu sebelum init Select2
                    $('#State').wrap('<div class="position-relative"></div>');
                    $('#State').select2({
                        placeholder: "Select Country",
                        allowClear: true,
                        dropdownParent: $('#State').parent()
                    });
                })
                .catch(error => {

                });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById("upload");
            const avatar = document.getElementById("uploadedAvatar");
            const resetBtn = document.querySelector(".account-image-reset");

            // Simpan gambar awal
            const originalImage = avatar.src;

            // Saat upload file
            fileInput.addEventListener("change", function() {
                const file = this.files[0];

                if (file) {
                    // Validasi ukuran (max 00KB)
                    if (file.size > 2000 * 1024) {
                        alert("Ukuran file maksimal 2MB!");
                        fileInput.value = "";
                        return;
                    }

                    // Validasi tipe file
                    const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
                    if (!allowedTypes.includes(file.type)) {
                        alert("Format harus JPG, PNG, atau GIF!");
                        fileInput.value = "";
                        return;
                    }

                    // Preview gambar
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatar.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Tombol reset
            resetBtn.addEventListener("click", function() {
                avatar.src = originalImage; // balik ke gambar awal
                fileInput.value = ""; // kosongkan input file
            });


        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const checkbox = document.getElementById("accountActivation");
            const button = document.querySelector(".deactivate-account");
            const form = document.getElementById("formAccountDeactivation");

            // Enable / disable button
            checkbox.addEventListener("change", function() {
                button.disabled = !this.checked;
            });

            // SweetAlert saat submit
            form.addEventListener("submit", function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This account will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // lanjut submit
                    }
                });
            });

        });
    </script>
@endsection
