@extends('Admin.Layouts.app')

@php $chartModeLabel = ucfirst($chartMode); @endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Dashboard</h4>
            <p class="mb-0 text-muted">Periode: {{ $periodeLabel }}</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if ($chartMode === 'harian')
                <form method="GET" action="{{ route('dashboard') }}" class="d-flex">
                    <input type="hidden" name="chart_mode" value="harian">
                    <input type="month" name="bulan" class="form-control form-control-sm" value="{{ $bulan }}"
                        onchange="this.form.submit()">
                </form>
            @elseif ($chartMode === 'bulanan')
                <form method="GET" action="{{ route('dashboard') }}" class="d-flex">
                    <input type="hidden" name="chart_mode" value="bulanan">
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" @selected($tahun == $y)>{{ $y }}</option>
                        @endfor
                    </select>
                </form>
            @endif

            <div class="btn-group" role="group">
                <a href="{{ route('dashboard', ['chart_mode' => 'harian']) }}"
                    class="btn btn-sm {{ $chartMode === 'harian' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Harian
                </a>
                <a href="{{ route('dashboard', ['chart_mode' => 'bulanan']) }}"
                    class="btn btn-sm {{ $chartMode === 'bulanan' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Bulanan
                </a>
                <a href="{{ route('dashboard', ['chart_mode' => 'tahunan']) }}"
                    class="btn btn-sm {{ $chartMode === 'tahunan' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Tahunan
                </a>
            </div>
        </div>
    </div>

    @can('finance.read')
        <h6 class="text-uppercase text-muted mb-3">Penjualan (Keuangan)</h6>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <span class="text-heading">Total Transaksi</span>
                        <h3 class="mb-0 mt-1">{{ number_format($totalTransaksi) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <span class="text-heading">Total Omzet</span>
                        <h3 class="mb-0 mt-1">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <span class="text-heading">Sudah Dibayar</span>
                        <h3 class="mb-0 mt-1 text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <span class="text-heading">Piutang</span>
                        <h3 class="mb-0 mt-1 text-danger">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">Omzet Penjualan {{ $chartModeLabel }}</h6>
                    </div>
                    <div class="card-body">
                        <div id="omzetChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">Status Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <div id="statusChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Barang Terlaris</h6>
                    </div>
                    <div class="card-body">
                        <div id="barangChart"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('stock-ins.read')
        <h6 class="text-uppercase text-muted mb-3">Barang Masuk</h6>
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <span class="text-heading">Total Transaksi Masuk</span>
                        <h3 class="mb-0 mt-1">{{ number_format($totalMasukTransaksi) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <span class="text-heading">Total Qty Masuk</span>
                        <h3 class="mb-0 mt-1 text-success">{{ number_format($totalMasukQty, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Barang Masuk {{ $chartModeLabel }}</h6>
                    </div>
                    <div class="card-body">
                        <div id="masukChart"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @canany(['stock-outs.read', 'delivery-notes.read'])
        <h6 class="text-uppercase text-muted mb-3">Barang Keluar & Surat Jalan</h6>
        <div class="row g-4 mb-4">
            @can('stock-outs.read')
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <span class="text-heading">Total Transaksi Keluar</span>
                            <h3 class="mb-0 mt-1">{{ number_format($totalKeluarTransaksi) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <span class="text-heading">Total Qty Keluar</span>
                            <h3 class="mb-0 mt-1 text-danger">{{ number_format($totalKeluarQty, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            @endcan
            @can('delivery-notes.read')
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <span class="text-heading">Total Surat Jalan</span>
                            <h3 class="mb-0 mt-1">{{ number_format($totalSuratJalan) }}</h3>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        <div class="row g-4">
            @can('stock-outs.read')
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">Barang Keluar {{ $chartModeLabel }}</h6>
                        </div>
                        <div class="card-body">
                            <div id="keluarChart"></div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('delivery-notes.read')
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">Status Surat Jalan</h6>
                        </div>
                        <div class="card-body">
                            <div id="suratJalanChart"></div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    @endcanany

    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dateLabels = @json($dateLabels);
            var omzetValues = @json($omzetValues);
            var barangLabels = @json($barangLabels);
            var barangValues = @json($barangValues);
            var statusCounts = @json($statusCounts);
            var masukValues = @json($masukValues);
            var keluarValues = @json($keluarValues);
            var suratJalanStatusCounts = @json($suratJalanStatusCounts);

            function renderChart(selector, options) {
                var el = document.querySelector(selector);
                if (el) {
                    new ApexCharts(el, options).render();
                }
            }

            renderChart('#omzetChart', {
                chart: { type: 'area', height: 320, toolbar: { show: false } },
                series: [{ name: 'Omzet', data: omzetValues }],
                xaxis: { categories: dateLabels },
                yaxis: { labels: { formatter: function (val) { return 'Rp ' + Number(val).toLocaleString('id-ID'); } } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                colors: ['#696cff'],
                tooltip: { y: { formatter: function (val) { return 'Rp ' + Number(val).toLocaleString('id-ID'); } } },
            });

            renderChart('#statusChart', {
                chart: { type: 'donut', height: 320 },
                series: [statusCounts.lunas, statusCounts.sebagian, statusCounts.belum_bayar],
                labels: ['Lunas', 'Sebagian', 'Belum Bayar'],
                colors: ['#71dd37', '#ffab00', '#ff3e1d'],
                legend: { position: 'bottom' },
            });

            renderChart('#barangChart', {
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                series: [{ name: 'Jumlah Terjual', data: barangValues }],
                xaxis: { categories: barangLabels },
                plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                colors: ['#696cff'],
                dataLabels: { enabled: true },
            });

            renderChart('#masukChart', {
                chart: { type: 'area', height: 280, toolbar: { show: false } },
                series: [{ name: 'Qty Masuk', data: masukValues }],
                xaxis: { categories: dateLabels },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                colors: ['#71dd37'],
            });

            renderChart('#keluarChart', {
                chart: { type: 'area', height: 280, toolbar: { show: false } },
                series: [{ name: 'Qty Keluar', data: keluarValues }],
                xaxis: { categories: dateLabels },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                colors: ['#ff3e1d'],
            });

            renderChart('#suratJalanChart', {
                chart: { type: 'donut', height: 280 },
                series: [suratJalanStatusCounts.draft, suratJalanStatusCounts.terkirim, suratJalanStatusCounts.selesai],
                labels: ['Draft', 'Terkirim', 'Selesai'],
                colors: ['#8592a3', '#ffab00', '#71dd37'],
                legend: { position: 'bottom' },
            });
        });
    </script>
@endsection
