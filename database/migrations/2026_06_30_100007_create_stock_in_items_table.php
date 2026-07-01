<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_in_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_in_id')->constrained('stock_ins')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('unit_id')->constrained('units');
            $table->decimal('qty_input', 12, 2);
            $table->decimal('qty_base', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_in_items');
    }
};
