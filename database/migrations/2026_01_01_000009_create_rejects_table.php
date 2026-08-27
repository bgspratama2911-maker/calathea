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
        Schema::create('rejects', function (Blueprint $table) {
            $table->id();
            $table->date('date')->default(now()->toDateString());
            $table->string('product_name');
            $table->string('category');
            $table->integer('quantity')->default(1);
            $table->string('unit')->default('Pcs');
            $table->decimal('estimated_loss', 15, 2)->default(0);
            $table->string('reason')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rejects');
    }
};
