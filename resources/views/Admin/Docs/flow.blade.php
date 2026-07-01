<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flow Diagram — {{ $settings->nama_perusahaan }}</title>
    <script src="https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fa;
            color: #222;
            padding: 24px;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header .brand img {
            max-height: 48px;
            max-width: 48px;
            object-fit: contain;
        }

        .page-header .brand h1 {
            font-size: 22px;
            font-weight: 700;
            color: #333;
        }

        .page-header .brand small {
            display: block;
            font-size: 12px;
            color: #888;
            font-weight: 400;
        }

        .btn-print {
            background: #4e73df;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover { background: #3a5bbf; }

        .section {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,.08);
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #4e73df;
            border-bottom: 2px solid #e8eef8;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }

        .mermaid { width: 100%; overflow: auto; }

        /* Legend */
        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 12px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #555;
        }
        .legend-dot {
            width: 12px; height: 12px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        /* Print */
        @media print {
            body { background: #fff; padding: 10px; font-size: 11px; }
            .btn-print { display: none !important; }
            .section {
                box-shadow: none;
                border: 1px solid #ddd;
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .page-header { margin-bottom: 14px; }
        }
    </style>
</head>
<body>

    <div class="page-header">
        <div class="brand">
            @if ($settings->logo)
                <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo">
            @endif
            <h1>
                {{ $settings->nama_perusahaan }}
                <small>Application Flow Diagram — Modul Inventory</small>
            </h1>
        </div>
        <button class="btn-print" onclick="window.print()">
            🖨️ Print / Save PDF
        </button>
    </div>

    {{-- ── 1. Alur Utama ────────────────────────────────────────── --}}
    <div class="section">
        <div class="section-title">Alur Utama Aplikasi</div>
        <div class="mermaid">
flowchart TD
    A([🔐 Login]) --> B[Dashboard]

    B --> DM[📦 Data Master]
    B --> BM[📥 Barang Masuk]
    B --> KS[🛒 Kasir Penjualan]
    B --> BK[📤 Barang Keluar Manual]
    B --> SO[🔍 Stok Opname]
    B --> LAP[📊 Laporan & Inventory]

    DM --> D1[Supplier]
    DM --> D2[Customer]
    DM --> D3[Jenis Barang]
    DM --> D4[Satuan & Konversi]
    DM --> D5[Data Barang / Item]

    BM -->|stok bertambah| SK[(📒 Kartu Stok)]
    BK -->|stok berkurang| SK
    SO -->|koreksi selisih| SK

    KS -->|simpan penjualan| PJ[Faktur Penjualan]
    PJ -->|otomatis buat| BO[Barang Keluar]
    PJ -->|otomatis buat| SJ[Surat Jalan — Draft]
    BO -->|stok berkurang| SK

    SJ --> SJ2[Terkirim]
    SJ2 --> SJ3[Selesai]

    SK --> LAP
    LAP --> EX1[📄 Export PDF]
    LAP --> EX2[📊 Export Excel]

    style A fill:#4e73df,color:#fff,stroke:none
    style B fill:#1cc88a,color:#fff,stroke:none
    style PJ fill:#f6c23e,color:#333,stroke:none
    style SK fill:#e8f4fd,color:#333,stroke:#4e73df
    style SJ fill:#fff3cd,color:#333,stroke:#f6c23e
    style SJ2 fill:#d4edda,color:#333,stroke:#1cc88a
    style SJ3 fill:#1cc88a,color:#fff,stroke:none
        </div>
    </div>

    {{-- ── 2. Alur Kasir Penjualan ─────────────────────────────── --}}
    <div class="section">
        <div class="section-title">Detail Alur Kasir Penjualan</div>
        <div class="mermaid">
flowchart LR
    A([Buka Modal Kasir]) --> B[Scan Barcode\natau Pilih Barang]
    B --> C{Stok cukup?}
    C -->|Tidak| ERR[❌ Tampil error\nstok tidak cukup]
    C -->|Ya| D[Tambah ke Cart]
    D --> E{Tambah barang\nlagi?}
    E -->|Ya| B
    E -->|Tidak| F[Input Pelanggan\n& Bayar]
    F --> G{Bayar ≥ Total?}
    G -->|Lunas| H1[Status: Lunas]
    G -->|Sebagian| H2[Status: Sebagian\nSisa jadi Piutang]
    G -->|Belum bayar| H3[Status: Belum Bayar]
    H1 & H2 & H3 --> I[(Simpan ke DB)]
    I --> J[Buat Faktur\nPenjualan INV-xxx]
    I --> K[Buat Barang Keluar\nBRG-KLR-xxx]
    I --> L[Buat Surat Jalan\nSJ-xxxx — Draft]
    K --> M[Potong Stok Item]
    M --> N[Catat Kartu Stok]

    style A fill:#4e73df,color:#fff,stroke:none
    style I fill:#1cc88a,color:#fff,stroke:none
    style ERR fill:#e74a3b,color:#fff,stroke:none
    style H1 fill:#1cc88a,color:#fff,stroke:none
    style H2 fill:#f6c23e,color:#333,stroke:none
    style H3 fill:#e74a3b,color:#fff,stroke:none
        </div>
    </div>

    {{-- ── 3. Alur Surat Jalan ─────────────────────────────────── --}}
    <div class="section">
        <div class="section-title">Alur Status Surat Jalan</div>
        <div class="mermaid">
flowchart LR
    A([Surat Jalan Dibuat]) --> B[🟡 DRAFT\nDibuat otomatis\nsaat Penjualan disimpan]
    B -->|Barang dikirim| C[🔵 TERKIRIM\nBarang dalam perjalanan]
    C -->|Pelanggan terima| D[🟢 SELESAI\nTransaksi selesai]

    B -.->|Bisa juga dibuat\nmanual dari\nBarang Keluar| B

    style A fill:#6c757d,color:#fff,stroke:none
    style B fill:#fff3cd,color:#333,stroke:#f6c23e,stroke-width:2px
    style C fill:#cce5ff,color:#333,stroke:#4e73df,stroke-width:2px
    style D fill:#d4edda,color:#333,stroke:#1cc88a,stroke-width:2px
        </div>
    </div>

    {{-- ── 4. Alur Stok ────────────────────────────────────────── --}}
    <div class="section">
        <div class="section-title">Alur Pergerakan Stok</div>
        <div class="mermaid">
flowchart LR
    BM[📥 Barang Masuk] -->|+qty| IT[(Stok Item)]
    KS[🛒 Penjualan] -->|-qty otomatis| IT
    BKM[📤 Barang Keluar\nManual] -->|-qty| IT
    OPN[🔍 Stok Opname] -->|koreksi ±| IT

    IT --> SC[(📒 Kartu Stok\nLedger Berjalan)]
    SC --> RP[📊 Laporan Inventory]

    style IT fill:#e8f4fd,color:#333,stroke:#4e73df,stroke-width:2px
    style SC fill:#e8f4fd,color:#333,stroke:#4e73df,stroke-width:2px
    style BM fill:#d4edda,color:#333,stroke:#1cc88a
    style KS fill:#fff3cd,color:#333,stroke:#f6c23e
    style BKM fill:#fde8e8,color:#333,stroke:#e74a3b
    style OPN fill:#e2d9f3,color:#333,stroke:#6f42c1
        </div>

        <div class="legend">
            <div class="legend-item"><div class="legend-dot" style="background:#d4edda;border:1px solid #1cc88a"></div> Stok Masuk (+)</div>
            <div class="legend-item"><div class="legend-dot" style="background:#fde8e8;border:1px solid #e74a3b"></div> Stok Keluar (−)</div>
            <div class="legend-item"><div class="legend-dot" style="background:#e2d9f3;border:1px solid #6f42c1"></div> Koreksi Opname (±)</div>
            <div class="legend-item"><div class="legend-dot" style="background:#e8f4fd;border:1px solid #4e73df"></div> Data Stok</div>
        </div>
    </div>

    <p style="text-align:center;color:#aaa;font-size:11px;margin-top:8px;">
        {{ $settings->nama_perusahaan }} · Digenerate {{ now()->format('d M Y H:i') }}
    </p>

    <script>
        mermaid.initialize({
            startOnLoad: true,
            theme: 'default',
            flowchart: { curve: 'basis', useMaxWidth: true },
            securityLevel: 'loose',
        });
    </script>
</body>
</html>
