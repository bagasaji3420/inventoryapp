# PRD - Modul Inventory (KAOS)

Modul tambahan di project Hosana untuk kelola stok barang (kain/tekstil): master data, transaksi masuk/keluar, surat jalan, kasir penjualan, kartu stok, dan stok opname.

Referensi: screenshot sistem existing di `inventory.prabudigital.com`.

## 1. Tujuan

Kelola alur inventory dari pencatatan master data, transaksi masuk/keluar barang, dokumen pengiriman (surat jalan), penjualan via kasir, hingga koreksi stok fisik (opname) dan laporan.

## 2. Keputusan & Asumsi (dari diskusi)

- Modul ini jadi bagian dari project Hosana (bukan project baru terpisah).
- Notifikasi stok minimum: **out of scope**, tidak dikerjakan dulu.
- Laporan: export pakai **dompdf** (PDF) dan **maatwebsite/excel** (Excel).
- Scan barcode: 2 mode toggle di UI kasir - `Scanner HP` (kamera, pakai JS library mis. html5-qrcode) dan `Scanner Kasir` (alat fisik, emulate keyboard input + Enter).
- **Kasir Penjualan adalah titik input utama** untuk transaksi jual: saat "Simpan Penjualan", sistem otomatis membuat record `sales` + `stock_outs` (pengurangan stok) + `delivery_notes` (status draft) dalam satu transaksi atomik.
- Surat Jalan: alur status `draft -> terkirim -> selesai`, otomatis dibuat (status draft) saat Penjualan disimpan.
- Pembayaran kasir: boleh kurang dari total (ada piutang/cicilan). Status pembayaran: `lunas` / `sebagian` / `belum_bayar`. Pembayaran tambahan (cicilan) dicatat di `sale_payments`.
- Satuan: perlu konversi multi-satuan (mis. 1 Yard = 0.9144 Meter) lewat tabel `unit_conversions`. Tiap barang punya satuan dasar, transaksi bisa input pakai satuan lain lalu dikonversi ke satuan dasar.
- Stok Opname: input stok fisik per item, sistem hitung selisih vs stok sistem, otomatis bikin adjustment entry di `stock_cards` agar saldo sinkron.

## 3. Alur Utama

1. **Barang Masuk** -> tambah stok -> auto-catat di Kartu Stok (kolom Masuk).
2. **Kasir Penjualan** -> simpan -> otomatis bikin `sales`, `stock_outs` (stok berkurang, auto-catat di Kartu Stok kolom Keluar), dan `delivery_notes` (draft).
3. **Surat Jalan**: draft -> terkirim (barang berangkat) -> selesai (diterima pelanggan).
4. **Stok Opname**: input stok fisik per item -> sistem hitung selisih -> auto-create adjustment entry di Kartu Stok.
5. **Laporan**: Stok, Barang Masuk, Barang Keluar, Penjualan - query dari `stock_cards` / `stock_ins` / `stock_outs` / `sales`, dengan export PDF (dompdf) & Excel (Laravel Excel).

## 4. Skema Tabel

### Data Master

**`suppliers`**
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| nama_supplier | varchar | |
| kontak_person | varchar | |
| telepon | varchar | |
| alamat | varchar | asumsi tambahan, belum ada di screenshot |
| status | enum(aktif/nonaktif) | |
| timestamps | | |

**`customers`** (Pelanggan)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| nama_pelanggan | varchar | |
| email | varchar | |
| telepon | varchar | |
| alamat | varchar | asumsi, dipakai utk alamat_tujuan default di Surat Jalan |
| status | enum(aktif/nonaktif) | |
| timestamps | | |

**`item_types`** (Jenis Barang)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| nama_jenis | varchar | |
| status | enum | |
| timestamps | | |

**`units`** (Satuan)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| nama_satuan | varchar | Yard, Meter, dst |
| status | enum | |
| timestamps | | |

**`unit_conversions`**
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| from_unit_id | FK units | |
| to_unit_id | FK units | |
| factor | decimal | qty_from x factor = qty_to |
| timestamps | | |

**`items`** (Data Barang)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| kode_barang | varchar unique | |
| barcode | varchar unique | |
| nama_barang | varchar | |
| satuan_id | FK units | satuan dasar/baku |
| jenis_id | FK item_types | |
| harga | decimal | harga jual default |
| stok_minimum | int | |
| stok | int (cache) | hasil agregasi stock_cards, di-update tiap transaksi |
| foto | varchar (path) | |
| status | enum | |
| timestamps | | |

### Transaksi

**`stock_ins`** (header Barang Masuk)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| no_transaksi | varchar unique | prefix `BRG-MSK`, format `BRG-MSK-yyyymmdd-xxx` |
| supplier_id | FK suppliers | |
| tanggal | date | |
| timestamps | | |

**`stock_in_items`** (detail, 1 no_transaksi bisa banyak barang)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| stock_in_id | FK stock_ins | |
| item_id | FK items | |
| unit_id | FK units | satuan saat input (bisa beda dari satuan dasar) |
| qty_input | int | jumlah sesuai satuan input |
| qty_base | int | hasil konversi ke satuan dasar (dipakai update stok) |

**`stock_outs`** (header Barang Keluar)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| no_transaksi | varchar unique | prefix `BRG-KLR`, format `BRG-KLR-yyyymmdd-xxx` |
| customer_id | FK customers | |
| sale_id | FK sales, nullable | terisi kalau dibuat otomatis dari Kasir Penjualan |
| tanggal | date | |
| keterangan | varchar nullable | |
| timestamps | | |

**`stock_out_items`**
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| stock_out_id | FK stock_outs | |
| item_id | FK items | |
| unit_id | FK units | |
| qty_input | int | |
| qty_base | int | |

**`delivery_notes`** (Surat Jalan)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| no_surat | varchar unique | prefix `SJ`, format `SJ-000x/MM/YYYY` |
| stock_out_id | FK stock_outs | sumber data barang & pelanggan |
| tanggal | date | |
| alamat_tujuan | varchar | |
| status | enum(draft/terkirim/selesai) | |
| timestamps | | |

> Items surat jalan diambil dari `stock_out_items` via `stock_out_id`, tidak duplikat tabel - kecuali nanti perlu kirim sebagian (qty beda dari stock_out).

### Penjualan (Kasir)

**`sales`**
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| no_faktur | varchar unique | prefix `INV`, format `INV-yyyymmdd0001` |
| customer_id | FK customers | |
| tanggal | date | |
| total | decimal | |
| status_pembayaran | enum(lunas/sebagian/belum_bayar) | |
| timestamps | | |

**`sale_items`**
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| sale_id | FK sales | |
| item_id | FK items | |
| qty | int | |
| harga | decimal | snapshot harga saat transaksi (bukan ambil dari items.harga, biar histori aman) |
| subtotal | decimal | |

**`sale_payments`**
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| sale_id | FK sales | |
| tanggal | date | |
| jumlah | decimal | |
| keterangan | varchar nullable | mis. "bayar awal di kasir" / "cicilan ke-2" |
| timestamps | | |

### Inventory

**`stock_cards`** (ledger, auto-generated, read-only dari sisi user)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| item_id | FK items | |
| tanggal | datetime | |
| jenis_transaksi | enum(masuk/keluar/opname) | |
| referensi | varchar | no_transaksi terkait |
| masuk | int nullable | |
| keluar | int nullable | |
| saldo | int | saldo berjalan setelah transaksi ini |
| timestamps | | |

**`stock_opnames`** (header)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| no_opname | varchar unique | prefix `OPN`, format `OPNyyyymmdd0001` |
| tanggal_opname | date | |
| petugas | varchar (atau FK users) | |
| status | enum(draft/selesai) | |
| timestamps | | |

**`stock_opname_items`** (detail)
| Field | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| stock_opname_id | FK stock_opnames | |
| item_id | FK items | |
| stok_sistem | int | snapshot stok sistem saat opname dibuat |
| stok_fisik | int | hasil hitung petugas |
| selisih | int | stok_fisik - stok_sistem |

**Total: 17 tabel.**

## 5. Daftar Kode Prefix Auto-Generated

| Tabel | Field | Prefix | Format | Contoh |
|---|---|---|---|---|
| `stock_ins` | no_transaksi | `BRG-MSK` | `BRG-MSK-yyyymmdd-xxx` | BRG-MSK-20260620-001 |
| `stock_outs` | no_transaksi | `BRG-KLR` | `BRG-KLR-yyyymmdd-xxx` | BRG-KLR-20260620-001 |
| `delivery_notes` | no_surat | `SJ` | `SJ-000x/MM/YYYY` | SJ-0004/06/2026 |
| `sales` | no_faktur | `INV` | `INV-yyyymmdd0001` | INV-202606300001 |
| `stock_opnames` | no_opname | `OPN` | `OPNyyyymmdd0001` | OPN202606190001 |

> Catatan: format belum konsisten - ada yang pakai dash, ada slash, ada nempel tanpa pemisah; ada yang counter reset per hari (`-xxx`) vs belum jelas reset per apa (`INV...0001`, `OPN...0001`). **Belum diputuskan** apakah mau diseragamkan atau tetap mengikuti persis sistem KAOS existing.

## 6. Role & Permission

Mengikuti pola permission yang sudah ada di Hosana (`module.read/create/update/delete`). Modul baru yang perlu permission:

`suppliers`, `customers`, `item-types`, `units`, `items`, `stock-ins`, `stock-outs`, `delivery-notes`, `sales`, `stock-opnames`, `reports`

## 7. Open Questions

- Format kode prefix mau diseragamkan atau ikut persis existing? (lihat bagian 5)
- `petugas` di `stock_opnames` - teks bebas atau FK ke `users`?
- Apakah Surat Jalan bisa kirim sebagian (qty beda dari stock_out_items)?
- Field tambahan lain yang belum tercakup di sini?
