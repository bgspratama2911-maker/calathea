<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reject;
use App\Models\RejectCategory;

class RejectSeeder extends Seeder
{
    /**
     * Seed initial categories for rejects.
     */
    public function run(): void
    {
        $defaultCategories = [
            'Bahan Baku Kadaluarsa',
            'Kesalahan Seduh / Human Error',
            'Kemasan / Cup Rusak',
            'Susu Basi / Tumpah',
            'Biji Kopi Gosong / Rusak',
            'Lain-lain',
        ];

        foreach ($defaultCategories as $catName) {
            RejectCategory::firstOrCreate(['name' => $catName]);
        }
    }
}
