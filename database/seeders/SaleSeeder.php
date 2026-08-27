<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleCategory;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    /**
     * Seed initial sales sample transactions and categories.
     */
    public function run(): void
    {
        $defaultCategories = [
            'Espresso Based',
            'Coffee Specialty',
            'Flavored Coffee',
            'Non-Coffee & Tea',
            'Botolan / Takeaway',
        ];

        foreach ($defaultCategories as $catName) {
            SaleCategory::firstOrCreate(['name' => $catName]);
        }

        if (Sale::count() > 0) {
            return;
        }

        $products = Sale::$defaultProducts;
        $methods = Sale::$paymentMethods;

        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            
            $randomKeys = array_rand($products, rand(3, 7));
            if (!is_array($randomKeys)) {
                $randomKeys = [$randomKeys];
            }

            foreach ($randomKeys as $key) {
                $item = $products[$key];
                $qty = rand(2, 12);
                $price = $item['default_price'];
                $total = $qty * $price;
                $payment = $methods[array_rand($methods)];

                Sale::create([
                    'date' => $date,
                    'product_name' => $item['name'],
                    'category' => $item['category'],
                    'quantity_sold' => $qty,
                    'price_per_unit' => $price,
                    'total_income' => $total,
                    'payment_method' => $payment,
                    'notes' => 'Penjualan kasir',
                ]);
            }
        }
    }
}
