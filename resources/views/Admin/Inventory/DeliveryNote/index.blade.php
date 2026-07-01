@extends('Admin.Layouts.app')

@section('content')
    <h4 class="mb-1">Surat Jalan</h4>
    <p class="mb-6 text-muted">Data Surat Jalan</p>

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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeliveryNoteModal"
                @cannot('delivery-notes.create') disabled @endcannot>
                Tambah Data
            </button>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('delivery-notes.export-excel') }}" class="btn btn-outline-success">
                    <i class="bx bxs-file-export"></i> Excel
                </a>
                <a href="{{ route('delivery-notes.export-pdf') }}" target="_blank" class="btn btn-outline-danger">
                    <i class="bx bxs-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <h6 class="fw-bold mb-4">DATA SURAT JALAN</h6>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Surat Jalan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Alamat Tujuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deliveryNotes as $i => $deliveryNote)
                            <tr>
                                <td>{{ $deliveryNotes->firstItem() + $i }}</td>
                                <td>{{ $deliveryNote->no_surat }}</td>
                                <td>{{ $deliveryNote->tanggal->format('Y-m-d') }}</td>
                                <td>{{ $deliveryNote->stockOut->customer->nama_pelanggan }}</td>
                                <td>{{ $deliveryNote->alamat_tujuan }}</td>
                                <td>
                                    <form action="{{ route('delivery-notes.update-status', $deliveryNote->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm"
                                            onchange="this.form.submit()"
                                            @cannot('delivery-notes.update') disabled @endcannot>
                                            <option value="draft" @selected($deliveryNote->status === 'draft')>Draft</option>
                                            <option value="terkirim" @selected($deliveryNote->status === 'terkirim')>Terkirim</option>
                                            <option value="selesai" @selected($deliveryNote->status === 'selesai')>Selesai</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                        <a href="{{ route('delivery-notes.print', $deliveryNote->id) }}" target="_blank"
                                            class="btn btn-icon btn-primary btn-sm shadow-none" title="Cetak PDF">
                                            <i class="bx bxs-file-pdf"></i>
                                        </a>
                                        <form action="{{ route('delivery-notes.destroy', $deliveryNote->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-danger btn-sm shadow-none"
                                                @cannot('delivery-notes.delete') disabled @endcannot>
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

            {{ $deliveryNotes->links() }}
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addDeliveryNoteModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('delivery-notes.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Surat Jalan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Transaksi Barang Keluar</label>
                        <select name="stock_out_id" class="form-select" required>
                            <option value="">Pilih Transaksi</option>
                            @foreach ($stockOutsWithoutNote as $stockOut)
                                <option value="{{ $stockOut->id }}">
                                    {{ $stockOut->no_transaksi }} - {{ $stockOut->customer->nama_pelanggan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required
                            value="{{ now()->toDateString() }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Tujuan</label>
                        <textarea name="alamat_tujuan" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('assets/custom-js/inventory/confirm-delete.js') }}"></script>
@endsection
