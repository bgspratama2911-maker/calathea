<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date')->default(now()->toDateString());
            $table->string('product_name');
            $table->string('category');
            $table->integer('quantity_sold')->default(1);
            $table->decimal('price_per_unit', 15, 2)->default(0);
            $table->decimal('total_income', 15, 2)->default(0);
            $table->string('payment_method')->default('QRIS');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
