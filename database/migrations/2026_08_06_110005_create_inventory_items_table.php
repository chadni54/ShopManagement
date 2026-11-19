<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->string('warehouse')->default('main');
            $table->integer('low_stock_threshold')->default(5);
            $table->string('status')->default('in_stock');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['product_id', 'warehouse']);
            $table->index(['status', 'quantity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
