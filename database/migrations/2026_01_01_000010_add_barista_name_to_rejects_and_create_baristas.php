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
        // 1. Create baristas table
        Schema::create('baristas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // 2. Add barista_name column to rejects table
        Schema::table('rejects', function (Blueprint $table) {
            $table->string('barista_name')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rejects', function (Blueprint $table) {
            $table->dropColumn('barista_name');
        });

        Schema::dropIfExists('baristas');
    }
};
