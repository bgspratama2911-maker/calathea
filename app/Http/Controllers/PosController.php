<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display Majoo Style POS Interface
     */
    public function index()
    {
        // Auto seed sample data & categories if empty
        if (SaleCategory::count() === 0 || Sale::count() === 0) {
            (new \Database\Seeders\SaleSeeder())->run();
        }

        // Available Categories
        $categories = Sale::getCategories();
        
        // Master Products list with realistic data & image placeholders matching screenshot
        $products = [
            ['id' => 1, 'name' => 'Tahu Campur Lamongan', 'category' => 'Makanan Utama', 'price' => 20000, 'discount' => 10, 'discount_amount' => 2000, 'badge' => '30%', 'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&auto=format&fit=crop&q=80'],
            ['id' => 2, 'name' => 'Sop Konro Banyuwangi', 'category' => 'Makanan Utama', 'price' => 25000, 'discount' => 0, 'discount_amount' => 0, 'badge' => 'Rp', 'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=300&auto=format&fit=crop&q=80'],
            ['id' => 3, 'name' => 'Es Teh Manis', 'category' => 'MINUMAN PANAS', 'price' => 5000, 'discount' => 0, 'discount_amount' => 0, 'badge' => '%', 'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=300&auto=format&fit=crop&q=80'],
            ['id' => 4, 'name' => 'Ayam Krispi Istimewa', 'category' => 'Makanan Utama', 'price' => 20000, 'discount' => 0, 'discount_amount' => 0, 'badge' => 'Rp', 'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=300&auto=format&fit=crop&q=80'],
            ['id' => 5, 'name' => 'Rendang Daging', 'category' => 'Makanan Utama', 'price' => 20000, 'discount' => 0, 'discount_amount' => 0, 'badge' => '30%', 'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=300&auto=format&fit=crop&q=80'],
            ['id' => 6, 'name' => 'Gurami Pedas Sayur Kuning', 'category' => 'Makanan Utama', 'price' => 40000, 'discount' => 0, 'discount_amount' => 0, 'badge' => '%', 'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=300&auto=format&fit=crop&q=80'],
            ['id' => 7, 'name' => 'Es Siwalan Pandan Hijau', 'category' => 'MINUMAN PANAS', 'price' => 5000, 'discount' => 0, 'discount_amount' => 0, 'badge' => '%', 'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=300&auto=format&fit=crop&q=80'],
            ['id' => 8, 'name' => 'Ronde Hangat', 'category' => 'MINUMAN PANAS', 'price' => 7500, 'discount' => 0, 'discount_amount' => 0, 'badge' => 'Rp', 'image' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=300&auto=format&fit=crop&q=80'],
            ['id' => 9, 'name' => 'Americano', 'category' => 'Espresso Based', 'price' => 18000, 'discount' => 0, 'discount_amount' => 0, 'badge' => '%', 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=300&auto=format&fit=crop&q=80'],
            ['id' => 10, 'name' => 'Calathea Coffee Specialty', 'category' => 'Coffee Specialty', 'price' => 25000, 'discount' => 0, 'discount_amount' => 0, 'badge' => '30%', 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=300&auto=format&fit=crop&q=80'],
            ['id' => 11, 'name' => 'Cappucino Hot', 'category' => 'Espresso Based', 'price' => 22000, 'discount' => 0, 'discount_amount' => 0, 'badge' => 'Rp', 'image' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=300&auto=format&fit=crop&q=80'],
            ['id' => 12, 'name' => 'Caramel Coffee', 'category' => 'Flavored Coffee', 'price' => 25000, 'discount' => 0, 'discount_amount' => 0, 'badge' => '%', 'image' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=300&auto=format&fit=crop&q=80'],
            ['id' => 13, 'name' => 'Matcha Latte', 'category' => 'Non-Coffee & Tea', 'price' => 22000, 'discount' => 0, 'discount_amount' => 0, 'badge' => 'Rp', 'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=300&auto=format&fit=crop&q=80'],
            ['id' => 14, 'name' => 'Es Teler Spesial', 'category' => 'MINUMAN PANAS', 'price' => 20000, 'discount' => 10, 'discount_amount' => 2000, 'badge' => '30%', 'image' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=300&auto=format&fit=crop&q=80'],
            ['id' => 15, 'name' => 'Nasi Putih', 'category' => 'Makanan Utama', 'price' => 3500, 'discount' => 0, 'discount_amount' => 0, 'badge' => 'Rp', 'image' => 'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=300&auto=format&fit=crop&q=80'],
        ];

        // Bottom drawer quick items (Ice creams / Desserts as in Majoo UI bottom bar)
        $quickItems = [
            ['id' => 101, 'name' => 'Strawberry Ice Cream', 'category' => 'Dessert', 'price' => 25000, 'badge' => '30%', 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&auto=format&fit=crop&q=80'],
            ['id' => 102, 'name' => 'Coffee Cookie Ice Cream', 'category' => 'Dessert', 'price' => 25000, 'badge' => '30%', 'image' => 'https://images.unsplash.com/photo-1570197788417-0e82375c9371?w=300&auto=format&fit=crop&q=80'],
            ['id' => 103, 'name' => '3 Color Ice Cream', 'category' => 'Dessert', 'price' => 25000, 'badge' => '30%', 'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=300&auto=format&fit=crop&q=80'],
            ['id' => 104, 'name' => 'Black Cherry Chocolate', 'category' => 'Dessert', 'price' => 25000, 'badge' => '30%', 'image' => 'https://images.unsplash.com/photo-1580915411954-282cb1b0d780?w=300&auto=format&fit=crop&q=80'],
        ];

        // Generate Order ID & today stats
        $todayCount = Sale::whereDate('date', Carbon::today())->count();
        $nextOrderNum = str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);

        $paymentMethods = Sale::$paymentMethods;

        return view('pos.index', compact(
            'categories',
            'products',
            'quickItems',
            'nextOrderNum',
            'paymentMethods'
        ));
    }

    /**
     * Store POS Order Transaction directly into Daily Sales (Penjualan Harian)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'customer_name' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.category' => 'required|string',
            'items.*.quantity_sold' => 'required|integer|min:1',
            'items.*.price_per_unit' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.notes' => 'nullable|string|max:255',
        ]);

        $todayDate = Carbon::today()->format('Y-m-d');
        $customerName = !empty($validated['customer_name']) ? trim($validated['customer_name']) : 'John Bonham';
        $paymentMethod = $validated['payment_method'];

        DB::beginTransaction();
        try {
            $createdSales = [];
            $grandTotal = 0;

            foreach ($validated['items'] as $item) {
                $qty = (int) $item['quantity_sold'];
                $unitPrice = (float) $item['price_per_unit'];
                $discount = isset($item['discount_amount']) ? (float) $item['discount_amount'] : 0;
                
                // Calculate item total income (Unit Price * Qty - Discount)
                $itemTotal = max(0, ($unitPrice * $qty) - $discount);
                $grandTotal += $itemTotal;

                // Make notes descriptive
                $notesArr = [];
                $notesArr[] = "Pelanggan: {$customerName}";
                if ($discount > 0) {
                    $notesArr[] = "Diskon: Rp " . number_format($discount, 0, ',', '.');
                }
                if (!empty($item['notes'])) {
                    $notesArr[] = trim($item['notes']);
                }

                $fullNotes = implode(' | ', $notesArr);

                // Auto create category if not exists
                SaleCategory::firstOrCreate(['name' => trim($item['category'])]);

                $sale = Sale::create([
                    'date' => $todayDate,
                    'product_name' => trim($item['product_name']),
                    'category' => trim($item['category']),
                    'quantity_sold' => $qty,
                    'price_per_unit' => $unitPrice,
                    'total_income' => $itemTotal,
                    'payment_method' => $paymentMethod,
                    'notes' => $fullNotes,
                ]);

                $createdSales[] = $sale;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi POS berhasil disimpan dan disinkronkan ke Penjualan Harian!',
                'grand_total' => $grandTotal,
                'items_count' => count($createdSales),
                'order_date' => Carbon::now()->translatedFormat('d F Y H:i:s'),
                'customer_name' => $customerName,
                'payment_method' => $paymentMethod
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi POS: ' . $e->getMessage()
            ], 500);
        }
    }
}
