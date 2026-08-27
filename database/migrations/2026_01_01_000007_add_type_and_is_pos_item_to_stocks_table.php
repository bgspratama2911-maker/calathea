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
        Schema::table('stocks', function (Blueprint $table) {
            $table->string('type')->default('raw_material')->after('category'); // 'raw_material' (Bahan Baku) or 'pos_menu' (Menu POS Kasir)
            $table->boolean('is_pos_item')->default(false)->after('type'); // boolean flag
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn(['type', 'is_pos_item']);
        });
    }
};
