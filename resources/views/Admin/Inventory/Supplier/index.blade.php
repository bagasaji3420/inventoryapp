@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Data Supplier</h4>
    <p class="mb-6 text-muted">Data Data Supplier</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal"
                @cannot('suppliers.create') disabled @endcannot>
                Tambah Data
            </button>
        </div>

        <div class="card-body">
            <h6 class="fw-bold mb-4">DATA DATA SUPPLIER</h6>

            <table id="supplierTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Supplier</th>
                        <th>Kontak Person</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $i => $supplier)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $supplier->nama_supplier }}</td>
                            <td>{{ $supplier->kontak_person }}</td>
                            <td>{{ $supplier->telepon }}</td>
                            <td>
                                <form action="{{ route('suppliers.toggle-status', $supplier->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch"
                                            onchange="this.form.submit()"
                                            {{ $supplier->status === 'aktif' ? 'checked' : '' }}
                                            @cannot('suppliers.update') disabled @endcannot>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-1 align-items-center">
                                    <button type="button" class="btn btn-icon btn-warning btn-sm shadow-none" data-bs-toggle="modal"
                                        data-bs-target="#editSupplierModal" @cannot('suppliers.update') disabled @endcannot
                                        onclick='setEdit(
                                            {{ $supplier->id }},
                                            @json($supplier->nama_supplier),
                                            @json($supplier->kontak_person),
                                            @json($supplier->telepon),
                                            @json($supplier->alamat)
                                        )'>
                                        <i class="bx bx-edit"></i>
                                    </button>

                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-danger btn-sm shadow-none"
                                            @cannot('suppliers.delete') disabled @endcannot>
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addSupplierModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('suppliers.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kontak Person</label>
                            <input type="text" name="kontak_person" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editSupplierModal">
        <div class="modal-dialog">
            <form method="POST" id="editSupplierForm" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_supplier" id="editNamaSupplier" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kontak Person</label>
                            <input type="text" name="kontak_person" id="editKontakPerson" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" id="editTelepon" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" id="editAlamat" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    @include('Admin.Layouts.Parts.datatables')
    <script src="{{ asset('assets/custom-js/inventory/confirm-delete.js') }}"></script>
    <script src="{{ asset('assets/custom-js/inventory/supplier.js') }}"></script>
@endsection
