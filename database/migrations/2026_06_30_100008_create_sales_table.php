<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->date('tanggal');
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status_pembayaran', ['lunas', 'sebagian', 'belum_bayar'])->default('belum_bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
