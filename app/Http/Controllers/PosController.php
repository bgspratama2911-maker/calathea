<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleCategory;
use App\Models\Stock;
use App\Models\StockCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Display Calathea POS Interface loaded from Stock table
     */
    public function index()
    {
        // Fetch products directly from stocks table that are marked for POS (is_pos_item = 1 or type = 'pos_menu')
        $dbStocks = Stock::where(function($q) {
            $q->where('is_pos_item', true)
              ->orWhere('type', 'pos_menu');
        })->orderBy('product_name', 'asc')->get();

        // Available Categories from POS Stock items
        $categories = $dbStocks->pluck('category')->unique()->values()->toArray();
        if (empty($categories)) {
            $categories = StockCategory::orderBy('name', 'asc')->pluck('name')->toArray();
        }
        // Available placeholder images based on keywords
        $sampleImages = [
            'coffee' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=300&auto=format&fit=crop&q=80',
            'espresso' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=300&auto=format&fit=crop&q=80',
            'cappucino' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=300&auto=format&fit=crop&q=80',
            'tea' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=300&auto=format&fit=crop&q=80',
            'milk' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=300&auto=format&fit=crop&q=80',
            'matcha' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=300&auto=format&fit=crop&q=80',
            'chocolate' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?w=300&auto=format&fit=crop&q=80',
            'food' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&auto=format&fit=crop&q=80',
            'ice' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&auto=format&fit=crop&q=80',
            'default' => 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?w=300&auto=format&fit=crop&q=80'
        ];

        $products = [];
        foreach ($dbStocks as $stk) {
            $nameLower = strtolower($stk->product_name);
            $catLower = strtolower($stk->category);
            
            $img = $sampleImages['default'];
            if (str_contains($nameLower, 'kopi') || str_contains($catLower, 'kopi')) {
                $img = $sampleImages['coffee'];
            } elseif (str_contains($nameLower, 'espresso') || str_contains($nameLower, 'americano')) {
                $img = $sampleImages['espresso'];
            } elseif (str_contains($nameLower, 'susu') || str_contains($catLower, 'susu') || str_contains($nameLower, 'creamer')) {
                $img = $sampleImages['milk'];
            } elseif (str_contains($nameLower, 'teh') || str_contains($nameLower, 'tea')) {
                $img = $sampleImages['tea'];
            } elseif (str_contains($nameLower, 'matcha')) {
                $img = $sampleImages['matcha'];
            } elseif (str_contains($nameLower, 'choco') || str_contains($nameLower, 'coklat')) {
                $img = $sampleImages['chocolate'];
            } elseif (str_contains($nameLower, 'ice') || str_contains($catLower, 'dessert') || str_contains($nameLower, 'es ')) {
                $img = $sampleImages['ice'];
            }

            $products[] = [
                'id' => $stk->id,
                'name' => $stk->product_name,
                'category' => $stk->category,
                'price' => (float) $stk->unit_price,
                'discount' => 0,
                'discount_amount' => 0,
                'badge' => $stk->current_stock > 0 ? ($stk->current_stock . ' ' . $stk->unit) : 'Habis',
                'current_stock' => $stk->current_stock,
                'unit' => $stk->unit,
                'image' => $img,
            ];
        }

        // Quick Items (Dessert/Ice Cream/Top items from Stock)
        $quickItems = array_slice($products, 0, 4);

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
