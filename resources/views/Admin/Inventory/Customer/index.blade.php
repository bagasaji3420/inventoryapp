@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Data Pelanggan</h4>
    <p class="mb-6 text-muted">Data Data Pelanggan</p>

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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal"
                @cannot('customers.create') disabled @endcannot>
                Tambah Data
            </button>
        </div>

        <div class="card-body">
            <h6 class="fw-bold mb-4">DATA DATA PELANGGAN</h6>

            <table id="customerTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $i => $customer)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $customer->nama_pelanggan }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->telepon }}</td>
                            <td>
                                <form action="{{ route('customers.toggle-status', $customer->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" role="switch"
                                            onchange="this.form.submit()"
                                            {{ $customer->status === 'aktif' ? 'checked' : '' }}
                                            @cannot('customers.update') disabled @endcannot>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-1 align-items-center">
                                    <button type="button" class="btn btn-icon btn-warning btn-sm shadow-none" data-bs-toggle="modal"
                                        data-bs-target="#editCustomerModal" @cannot('customers.update') disabled @endcannot
                                        onclick='setEdit(
                                            {{ $customer->id }},
                                            @json($customer->nama_pelanggan),
                                            @json($customer->email),
                                            @json($customer->telepon),
                                            @json($customer->alamat)
                                        )'>
                                        <i class="bx bx-edit"></i>
                                    </button>

                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-danger btn-sm shadow-none"
                                            @cannot('customers.delete') disabled @endcannot>
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
    <div class="modal fade" id="addCustomerModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('customers.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
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
    <div class="modal fade" id="editCustomerModal">
        <div class="modal-dialog">
            <form method="POST" id="editCustomerForm" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" id="editNamaPelanggan" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="telepon" id="editTeleponCustomer" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" id="editAlamatCustomer" class="form-control"></textarea>
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
    <script src="{{ asset('assets/custom-js/inventory/customer.js') }}"></script>
@endsection
