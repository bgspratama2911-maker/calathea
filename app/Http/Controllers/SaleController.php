<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleCategory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Tampilkan Halaman Rekap Penjualan Kopi (Harian, Mingguan, Bulanan)
     */
    public function index(Request $request)
    {
        // Seed default sale categories only if empty (do not seed sales transactions)
        if (SaleCategory::count() === 0) {
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
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $productName = $request->input('product_name');
        $category = $request->input('category');

        $query = Sale::query();

        if (!empty($startDate)) {
            $query->whereDate('date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('date', '<=', $endDate);
        }

        if (!empty($productName)) {
            $query->where('product_name', $productName);
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        $allFilteredSales = (clone $query)->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        $sales = (clone $query)->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        // 1. KPI Penjualan HARIAN (Today)
        $today = Carbon::today()->format('Y-m-d');
        $dailySalesQuery = Sale::whereDate('date', $today);
        $dailyIncome = $dailySalesQuery->sum('total_income');
        $dailyCups = $dailySalesQuery->sum('quantity_sold');

        // 2. KPI Penjualan MINGGUAN (This Week)
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $weeklySalesQuery = Sale::whereBetween('date', [$startOfWeek, $endOfWeek]);
        $weeklyIncome = $weeklySalesQuery->sum('total_income');
        $weeklyCups = $weeklySalesQuery->sum('quantity_sold');

        // 3. KPI Penjualan BULANAN (This Month)
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $monthlySalesQuery = Sale::whereYear('date', $currentYear)->whereMonth('date', $currentMonth);
        $monthlyIncome = $monthlySalesQuery->sum('total_income');
        $monthlyCups = $monthlySalesQuery->sum('quantity_sold');

        // 4. Produk Terlaris Bulan Ini (Best Seller)
        $bestSeller = Sale::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->selectRaw('product_name, SUM(quantity_sold) as total_qty, SUM(total_income) as total_rev')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->first();

        $bestSellerName = $bestSeller ? $bestSeller->product_name : '-';
        $bestSellerQty = $bestSeller ? $bestSeller->total_qty : 0;

        // 5. Total Ter-filter (calculated from full filtered set)
        $filteredIncome = $allFilteredSales->sum('total_income');
        $filteredCups = $allFilteredSales->sum('quantity_sold');

        // 6. Ringkasan Total Kopi yang Laku (Grouped by Product Name)
        $productBreakdown = $allFilteredSales->groupBy('product_name')->map(function ($items, $pName) use ($filteredCups) {
            $totalQty = $items->sum('quantity_sold');
            $totalRev = $items->sum('total_income');
            $percentage = $filteredCups > 0 ? ($totalQty / $filteredCups) * 100 : 0;
            $cat = $items->first()->category ?? '-';

            return [
                'name' => $pName,
                'category' => $cat,
                'total_qty' => $totalQty,
                'total_rev' => $totalRev,
                'percentage' => round($percentage, 1),
            ];
        })->sortByDesc('total_qty');

        // 7. Chart Data Harian (Trend Penjualan Harian)
        $dailyGrouped = $allFilteredSales->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m-d');
        })->map(function ($items) {
            return $items->sum('total_income');
        })->sortKeys();

        $dailyChartLabels = [];
        $dailyChartData = [];
        foreach ($dailyGrouped as $dateStr => $sumAmount) {
            $dailyChartLabels[] = Carbon::parse($dateStr)->format('d M Y');
            $dailyChartData[] = (float) $sumAmount;
        }

        // 8. Top 5 Produk Terlaris (Chart)
        $top5Products = $allFilteredSales->groupBy('product_name')->map(function ($items, $pName) {
            return $items->sum('quantity_sold');
        })->sortByDesc(function ($qty) { return $qty; })->take(5);

        $top5ChartLabels = array_keys($top5Products->toArray());
        $top5ChartData = array_values($top5Products->toArray());

        $products = Sale::$defaultProducts;
        $productNames = Sale::getProductNames();
        $categories = Sale::getCategories();
        $paymentMethods = Sale::$paymentMethods;

        return view('sales.index', compact(
            'sales',
            'dailyIncome',
            'dailyCups',
            'weeklyIncome',
            'weeklyCups',
            'monthlyIncome',
            'monthlyCups',
            'bestSellerName',
            'bestSellerQty',
            'filteredIncome',
            'filteredCups',
            'productBreakdown',
            'products',
            'productNames',
            'categories',
            'paymentMethods',
            'startDate',
            'endDate',
            'productName',
            'category',
            'dailyChartLabels',
            'dailyChartData',
            'top5ChartLabels',
            'top5ChartData'
        ));
    }

    /**
     * Simpan Kategori Penjualan Baru
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:sale_categories,name',
        ]);

        SaleCategory::create([
            'name' => trim($validated['name']),
        ]);

        return redirect()->route('sales.index')->with('success', 'Kategori penjualan baru "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Simpan Transaksi Penjualan Kopi Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'product_name' => 'required|string',
            'category' => 'required|string',
            'quantity_sold' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:255',
        ]);

        // Auto add category if not exists
        SaleCategory::firstOrCreate(['name' => trim($validated['category'])]);

        $validated['total_income'] = $validated['quantity_sold'] * $validated['price_per_unit'];

        Sale::create($validated);

        return redirect()->route('sales.index')->with('success', 'Data penjualan kopi berhasil dicatat!');
    }

    /**
     * Update Transaksi Penjualan
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'product_name' => 'required|string',
            'category' => 'required|string',
            'quantity_sold' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:255',
        ]);

        // Auto add category if not exists
        SaleCategory::firstOrCreate(['name' => trim($validated['category'])]);

        $validated['total_income'] = $validated['quantity_sold'] * $validated['price_per_unit'];

        $sale->update($validated);

        return redirect()->route('sales.index')->with('success', 'Data penjualan kopi berhasil diperbarui!');
    }

    /**
     * Hapus Data Penjualan
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Data penjualan kopi berhasil dihapus!');
    }

    /**
     * Bulk Delete (Hapus Banyak Data Transaksi Penjualan)
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:sales,id',
        ]);

        $count = Sale::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('sales.index')->with('success', "Sebanyak {$count} data transaksi penjualan berhasil dihapus!");
    }

    /**
     * Export PDF Laporan Penjualan Kopi (Harian / Mingguan / Bulanan)
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $productName = $request->input('product_name');
        $category = $request->input('category');

        $query = Sale::query();

        if (!empty($startDate)) {
            $query->whereDate('date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('date', '<=', $endDate);
        }

        if (!empty($productName)) {
            $query->where('product_name', $productName);
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        $sales = (clone $query)->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $totalIncome = $sales->sum('total_income');
        $totalCups = $sales->sum('quantity_sold');

        $productBreakdown = $sales->groupBy('product_name')->map(function ($items, $pName) use ($totalCups) {
            $totalQty = $items->sum('quantity_sold');
            $totalRev = $items->sum('total_income');
            $percentage = $totalCups > 0 ? ($totalQty / $totalCups) * 100 : 0;
            $cat = $items->first()->category ?? '-';

            return [
                'name' => $pName,
                'category' => $cat,
                'total_qty' => $totalQty,
                'total_rev' => $totalRev,
                'percentage' => round($percentage, 1),
            ];
        })->sortByDesc('total_qty');

        $pdfData = [
            'sales' => $sales,
            'totalIncome' => $totalIncome,
            'totalCups' => $totalCups,
            'productBreakdown' => $productBreakdown,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'productName' => $productName,
            'category' => $category,
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
        ];

        $pdf = Pdf::loadView('sales.pdf', $pdfData)->setPaper('a4', 'portrait');

        $filename = 'Laporan_Penjualan_Kopi_Calathea_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
