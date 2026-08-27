<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;
use App\Models\StockCategory;

class StockSeeder extends Seeder
{
    /**
     * Seed initial categories and 19 cafe stock products.
     */
    public function run(): void
    {
        // Seed default categories
        $defaultCategories = [
            'Biji Kopi',
            'Susu & Creamer',
            'Powder & Bubuk',
            'Syrup & Perasa',
            'Cup & Packaging',
            'Pemanis & Lainnya',
        ];

        foreach ($defaultCategories as $catName) {
            StockCategory::firstOrCreate(['name' => $catName]);
        }

        // Seed default products
        foreach (Stock::$defaultProducts as $item) {
            Stock::firstOrCreate(
                ['product_name' => $item['product_name']],
                [
                    'category' => $item['category'],
                    'current_stock' => $item['current_stock'],
                    'minimum_stock' => $item['minimum_stock'],
                    'unit' => $item['unit'],
                    'unit_price' => $item['unit_price'],
                    'last_restock_date' => now()->toDateString(),
                    'notes' => 'Stok awal cafe',
                ]
            );
        }
    }
}
