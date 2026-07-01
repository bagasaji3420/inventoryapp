<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Inventory\SupplierController;
use App\Http\Controllers\Inventory\CustomerController;
use App\Http\Controllers\Inventory\ItemTypeController;
use App\Http\Controllers\Inventory\UnitController;
use App\Http\Controllers\Inventory\UnitConversionController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\Inventory\StockInController;
use App\Http\Controllers\Inventory\StockOutController;
use App\Http\Controllers\Inventory\DeliveryNoteController;
use App\Http\Controllers\Inventory\StockOpnameController;
use App\Http\Controllers\Inventory\StockCardController;
use App\Http\Controllers\Inventory\SaleController;

// Data Master: Supplier
Route::resource('suppliers', SupplierController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware('permission:suppliers.read');
Route::patch('/suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])
    ->name('suppliers.toggle-status')
    ->middleware('permission:suppliers.update');

// Data Master: Pelanggan
Route::resource('customers', CustomerController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware('permission:customers.read');
Route::patch('/customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])
    ->name('customers.toggle-status')
    ->middleware('permission:customers.update');

// Data Master: Jenis Barang
Route::resource('item-types', ItemTypeController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware('permission:item-types.read');
Route::patch('/item-types/{item_type}/toggle-status', [ItemTypeController::class, 'toggleStatus'])
    ->name('item-types.toggle-status')
    ->middleware('permission:item-types.update');

// Data Master: Satuan
Route::resource('units', UnitController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware('permission:units.read');
Route::patch('/units/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])
    ->name('units.toggle-status')
    ->middleware('permission:units.update');

// Data Master: Konversi Satuan
Route::resource('unit-conversions', UnitConversionController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware('permission:units.read');

// Data Master: Data Barang
Route::resource('items', ItemController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware('permission:items.read');
Route::patch('/items/{item}/toggle-status', [ItemController::class, 'toggleStatus'])
    ->name('items.toggle-status')
    ->middleware('permission:items.update');
Route::get('/items/{item}/barcode', [ItemController::class, 'printBarcode'])
    ->name('items.barcode')
    ->middleware('permission:items.read');

// Transaksi: Barang Masuk
Route::resource('stock-ins', StockInController::class)
    ->only(['index', 'store', 'destroy'])
    ->middleware('permission:stock-ins.read');
Route::get('/stock-ins/export/excel', [StockInController::class, 'exportExcel'])
    ->name('stock-ins.export-excel')
    ->middleware('permission:stock-ins.read');
Route::get('/stock-ins/export/pdf', [StockInController::class, 'exportPdf'])
    ->name('stock-ins.export-pdf')
    ->middleware('permission:stock-ins.read');

// Transaksi: Barang Keluar
Route::resource('stock-outs', StockOutController::class)
    ->only(['index', 'store', 'destroy'])
    ->middleware('permission:stock-outs.read');
Route::get('/stock-outs/export/excel', [StockOutController::class, 'exportExcel'])
    ->name('stock-outs.export-excel')
    ->middleware('permission:stock-outs.read');
Route::get('/stock-outs/export/pdf', [StockOutController::class, 'exportPdf'])
    ->name('stock-outs.export-pdf')
    ->middleware('permission:stock-outs.read');

// Transaksi: Surat Jalan
Route::resource('delivery-notes', DeliveryNoteController::class)
    ->only(['index', 'store', 'destroy'])
    ->middleware('permission:delivery-notes.read');
Route::patch('/delivery-notes/{delivery_note}/status', [DeliveryNoteController::class, 'updateStatus'])
    ->name('delivery-notes.update-status')
    ->middleware('permission:delivery-notes.update');
Route::get('/delivery-notes/{delivery_note}/print', [DeliveryNoteController::class, 'printPdf'])
    ->name('delivery-notes.print')
    ->middleware('permission:delivery-notes.read');
Route::get('/delivery-notes/export/excel', [DeliveryNoteController::class, 'exportExcel'])
    ->name('delivery-notes.export-excel')
    ->middleware('permission:delivery-notes.read');
Route::get('/delivery-notes/export/pdf', [DeliveryNoteController::class, 'exportPdf'])
    ->name('delivery-notes.export-pdf')
    ->middleware('permission:delivery-notes.read');

// Inventory: Stok Opname
Route::get('/stock-opnames', [StockOpnameController::class, 'index'])
    ->name('stock-opnames.index')
    ->middleware('permission:stock-opnames.read');
Route::get('/stock-opnames/{stock_opname}', [StockOpnameController::class, 'show'])
    ->name('stock-opnames.show')
    ->middleware('permission:stock-opnames.read');
Route::post('/stock-opnames', [StockOpnameController::class, 'store'])
    ->name('stock-opnames.store')
    ->middleware('permission:stock-opnames.create');

// Inventory: Kartu Stok
Route::get('/stock-cards', [StockCardController::class, 'index'])
    ->name('stock-cards.index')
    ->middleware('permission:stock-cards.read');
Route::get('/stock-cards-report', [StockCardController::class, 'report'])
    ->name('stock-cards.report')
    ->middleware('permission:stock-cards.read');
Route::get('/stock-cards/{item}/report', [StockCardController::class, 'itemReport'])
    ->name('stock-cards.item-report')
    ->middleware('permission:stock-cards.read');
Route::get('/stock-cards/{item}/export-excel', [StockCardController::class, 'itemExportExcel'])
    ->name('stock-cards.item-export-excel')
    ->middleware('permission:stock-cards.read');
Route::get('/stock-cards/{item}', [StockCardController::class, 'show'])
    ->name('stock-cards.show')
    ->middleware('permission:stock-cards.read');

// Penjualan: Kasir
Route::get('/sales', [SaleController::class, 'index'])
    ->name('sales.index')
    ->middleware('permission:sales.read');
Route::get('/sales/export/excel', [SaleController::class, 'exportExcel'])
    ->name('sales.export-excel')
    ->middleware('permission:sales.read');
Route::get('/sales/export/pdf', [SaleController::class, 'exportPdf'])
    ->name('sales.export-pdf')
    ->middleware('permission:sales.read');
Route::get('/sales/{sale}', [SaleController::class, 'show'])
    ->name('sales.show')
    ->middleware('permission:sales.read');
Route::post('/sales', [SaleController::class, 'store'])
    ->name('sales.store')
    ->middleware('permission:sales.create');
Route::post('/sales/{sale}/payments', [SaleController::class, 'addPayment'])
    ->name('sales.payments.store')
    ->middleware('permission:sales.update');
