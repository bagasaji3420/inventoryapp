@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Pengaturan</h4>
    <p class="mb-6 text-muted">Atur profil perusahaan dan awalan (prefix) nomor otomatis untuk tiap jenis transaksi</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.codes.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Profil Perusahaan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" class="form-control"
                            value="{{ old('nama_perusahaan', $settings->nama_perusahaan) }}" maxlength="100" required>
                        <small class="text-muted">Tampil sebagai judul besar di kop cetak dokumen (mis. Surat Jalan)</small>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Alamat Perusahaan</label>
                        <input type="text" name="alamat_perusahaan" class="form-control"
                            value="{{ old('alamat_perusahaan', $settings->alamat_perusahaan) }}" maxlength="255">
                        <small class="text-muted">Tampil di bawah nama perusahaan pada kop cetak dokumen</small>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Logo Perusahaan</label>
                        <input type="file" name="logo" id="logoInput" class="form-control" accept="image/*">
                        <small class="text-muted">Dipakai untuk favicon, logo sidebar, dan logo cetak Surat Jalan (otomatis disesuaikan maks. 100x100px)</small>
                        <div class="mt-2">
                            <img id="logoPreview" src="{{ $settings->logo ? asset('storage/' . $settings->logo) : '' }}"
                                alt="Preview Logo" class="img-thumbnail {{ $settings->logo ? '' : 'd-none' }}"
                                style="max-width: 100px; max-height: 100px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Kode Transaksi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Kode Barang Masuk</label>
                        <input type="text" name="kode_barang_masuk" class="form-control code-prefix-input"
                            value="{{ old('kode_barang_masuk', $settings->kode_barang_masuk) }}" maxlength="20"
                            data-example-suffix="-20260701-001" data-example-target="exampleKodeBarangMasuk"
                            required>
                        <small class="text-muted" id="exampleKodeBarangMasuk">Contoh hasil: {{ $settings->kode_barang_masuk }}-20260701-001</small>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Kode Barang Keluar</label>
                        <input type="text" name="kode_barang_keluar" class="form-control code-prefix-input"
                            value="{{ old('kode_barang_keluar', $settings->kode_barang_keluar) }}" maxlength="20"
                            data-example-suffix="-20260701-001" data-example-target="exampleKodeBarangKeluar"
                            required>
                        <small class="text-muted" id="exampleKodeBarangKeluar">Contoh hasil: {{ $settings->kode_barang_keluar }}-20260701-001</small>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Kode Surat Jalan</label>
                        <input type="text" name="kode_surat_jalan" class="form-control code-prefix-input"
                            value="{{ old('kode_surat_jalan', $settings->kode_surat_jalan) }}" maxlength="20"
                            data-example-suffix="-0001/07/2026" data-example-target="exampleKodeSuratJalan"
                            required>
                        <small class="text-muted" id="exampleKodeSuratJalan">Contoh hasil: {{ $settings->kode_surat_jalan }}-0001/07/2026</small>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Kode Penjualan</label>
                        <input type="text" name="kode_penjualan" class="form-control code-prefix-input"
                            value="{{ old('kode_penjualan', $settings->kode_penjualan) }}" maxlength="20"
                            data-example-suffix="-202607010001" data-example-target="exampleKodePenjualan" required>
                        <small class="text-muted" id="exampleKodePenjualan">Contoh hasil: {{ $settings->kode_penjualan }}-202607010001</small>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Kode Stok Opname</label>
                        <input type="text" name="kode_stok_opname" class="form-control code-prefix-input"
                            value="{{ old('kode_stok_opname', $settings->kode_stok_opname) }}" maxlength="20"
                            data-example-suffix="202607010001" data-example-target="exampleKodeStokOpname"
                            required>
                        <small class="text-muted" id="exampleKodeStokOpname">Contoh hasil: {{ $settings->kode_stok_opname }}202607010001</small>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <script src="{{ asset('assets/custom-js/inventory/code-prefix-input.js') }}"></script>
    <script>
        document.getElementById('logoInput').addEventListener('change', function () {
            const preview = document.getElementById('logoPreview');
            if (!this.files || !this.files[0]) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(this.files[0]);
        });
    </script>
@endsection
