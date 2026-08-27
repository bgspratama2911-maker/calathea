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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('item_name');
            $table->string('category');
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('Pcs');
            $table->enum('condition', ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Habis'])->default('Baik');
            $table->date('purchase_date')->default(now()->toDateString());
            $table->decimal('price', 15, 2)->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
