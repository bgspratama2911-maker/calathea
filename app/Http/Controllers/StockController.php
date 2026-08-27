<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockCategory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StockController extends Controller
{
    /**
     * Tampilkan Halaman Monitoring Stok Bahan Baku
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $type = $request->input('type'); // 'all', 'raw_material', 'pos_menu'
        $status = $request->input('status');

        $query = Stock::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        if (!empty($type) && $type !== 'all') {
            if ($type === 'pos_menu') {
                $query->where(function($q) {
                    $q->where('type', 'pos_menu')
                      ->orWhere('is_pos_item', true);
                });
            } else {
                $query->where(function($q) {
                    $q->where('type', 'raw_material')
                      ->where('is_pos_item', false);
                });
            }
        }

        $stocks = (clone $query)->orderBy('product_name', 'asc')->get();

        // Filter by computed status if requested
        if (!empty($status)) {
            $stocks = $stocks->filter(function($item) use ($status) {
                return strtolower($item->status) === strtolower($status);
            });
        }

        // Calculation for KPI Cards
        $totalProducts = $stocks->count();
        $totalStockUnits = $stocks->sum('current_stock');
        $totalStockValue = $stocks->sum(function($item) {
            return $item->current_stock * $item->unit_price;
        });

        // Warning alerts count
        $lowStockCount = $stocks->filter(function($item) {
            return $item->current_stock <= $item->minimum_stock && $item->current_stock > 0;
        })->count();

        $outOfStockCount = $stocks->filter(function($item) {
            return $item->current_stock <= 0;
        })->count();

        $rawMaterialCount = Stock::where('type', 'raw_material')->where('is_pos_item', false)->count();
        $posMenuCount = Stock::where(function($q) {
            $q->where('type', 'pos_menu')->orWhere('is_pos_item', true);
        })->count();

        $categories = Stock::getCategories();
        $units = Stock::$units;

        return view('stocks.index', compact(
            'stocks',
            'totalProducts',
            'totalStockUnits',
            'totalStockValue',
            'lowStockCount',
            'outOfStockCount',
            'rawMaterialCount',
            'posMenuCount',
            'categories',
            'units',
            'search',
            'category',
            'type',
            'status'
        ));
    }

    /**
     * Simpan Kategori Stok Baru
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:stock_categories,name',
        ]);

        StockCategory::create([
            'name' => trim($validated['name']),
        ]);

        return redirect()->route('stocks.index')->with('success', 'Kategori stok baru "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Simpan Produk Stok Bahan Baku Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255|unique:stocks,product_name',
            'category' => 'required|string',
            'type' => 'required|in:raw_material,pos_menu',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'last_restock_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        // Auto determine is_pos_item flag
        $validated['is_pos_item'] = ($validated['type'] === 'pos_menu');

        // Auto add category if not exists
        StockCategory::firstOrCreate(['name' => trim($validated['category'])]);

        Stock::create($validated);

        $label = $validated['type'] === 'pos_menu' ? 'Menu POS Kasir' : 'Bahan Baku';
        return redirect()->route('stocks.index')->with('success', "Item stok ({$label}) berhasil ditambahkan!");
    }

    /**
     * Update Produk Stok
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255|unique:stocks,product_name,' . $stock->id,
            'category' => 'required|string',
            'type' => 'required|in:raw_material,pos_menu',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'unit_price' => 'required|numeric|min:0',
            'last_restock_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        // Auto determine is_pos_item flag
        $validated['is_pos_item'] = ($validated['type'] === 'pos_menu');

        // Auto add category if not exists
        StockCategory::firstOrCreate(['name' => trim($validated['category'])]);

        $stock->update($validated);

        return redirect()->route('stocks.index')->with('success', 'Data stok produk berhasil diperbarui!');
    }

    /**
     * Quick Restock / Kurangi Stok (Penyesuaian Stok Cepat)
     */
    public function adjustStock(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'action' => 'required|in:add,subtract',
            'amount' => 'required|integer|min:1',
        ]);

        if ($validated['action'] === 'add') {
            $stock->current_stock += $validated['amount'];
            $stock->last_restock_date = now()->toDateString();
            $msg = "Stok {$stock->product_name} berhasil ditambah (+{$validated['amount']} {$stock->unit})!";
        } else {
            $stock->current_stock = max(0, $stock->current_stock - $validated['amount']);
            $msg = "Stok {$stock->product_name} berhasil dikurangi (-{$validated['amount']} {$stock->unit})!";
        }

        $stock->save();

        return redirect()->route('stocks.index')->with('success', $msg);
    }

    /**
     * Hapus Produk Stok
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Produk stok bahan baku berhasil dihapus!');
    }

    /**
     * Export PDF Stok Bahan Baku
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $type = $request->input('type');
        $status = $request->input('status');

        $query = Stock::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        if (!empty($type) && $type !== 'all') {
            if ($type === 'pos_menu') {
                $query->where(function($q) {
                    $q->where('type', 'pos_menu')
                      ->orWhere('is_pos_item', true);
                });
            } else {
                $query->where(function($q) {
                    $q->where('type', 'raw_material')
                      ->where('is_pos_item', false);
                });
            }
        }

        $stocks = (clone $query)->orderBy('product_name', 'asc')->get();

        if (!empty($status)) {
            $stocks = $stocks->filter(function($item) use ($status) {
                return strtolower($item->status) === strtolower($status);
            });
        }

        $totalProducts = $stocks->count();
        $totalStockUnits = $stocks->sum('current_stock');
        $totalStockValue = $stocks->sum(function($item) {
            return $item->current_stock * $item->unit_price;
        });

        $pdfData = [
            'stocks' => $stocks,
            'totalProducts' => $totalProducts,
            'totalStockUnits' => $totalStockUnits,
            'totalStockValue' => $totalStockValue,
            'search' => $search,
            'category' => $category,
            'type' => $type,
            'status' => $status,
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
        ];

        $pdf = Pdf::loadView('stocks.pdf', $pdfData)->setPaper('a4', 'portrait');

        $filename = 'Laporan_Stok_Bahan_Baku_Calathea_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
