<?php

namespace App\Http\Controllers;

use App\Models\Reject;
use App\Models\RejectCategory;
use App\Models\Barista;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RejectController extends Controller
{
    /**
     * Tampilkan Halaman Monitoring Bahan Reject / Waste
     */
    public function index(Request $request)
    {
        // Auto seed default categories if empty
        if (RejectCategory::count() === 0) {
            (new \Database\Seeders\RejectSeeder())->run();
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');

        $query = Reject::query();

        if (!empty($startDate)) {
            $query->whereDate('date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('date', '<=', $endDate);
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        $rejects = (clone $query)->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        // 1. KPI Reject HARIAN (Today)
        $today = Carbon::today()->format('Y-m-d');
        $dailyRejectQuery = Reject::whereDate('date', $today);
        $dailyLoss = $dailyRejectQuery->sum('estimated_loss');
        $dailyQty = $dailyRejectQuery->sum('quantity');

        // 2. KPI Reject MINGGUAN (This Week)
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $weeklyRejectQuery = Reject::whereBetween('date', [$startOfWeek, $endOfWeek]);
        $weeklyLoss = $weeklyRejectQuery->sum('estimated_loss');
        $weeklyQty = $weeklyRejectQuery->sum('quantity');

        // 3. KPI Reject BULANAN (This Month)
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $monthlyRejectQuery = Reject::whereYear('date', $currentYear)->whereMonth('date', $currentMonth);
        $monthlyLoss = $monthlyRejectQuery->sum('estimated_loss');
        $monthlyQty = $monthlyRejectQuery->sum('quantity');

        // Total Ter-filter
        $filteredLoss = $rejects->sum('estimated_loss');
        $filteredQty = $rejects->sum('quantity');

        // Category Breakdown
        $categoryBreakdown = $rejects->groupBy('category')->map(function ($items, $catName) use ($filteredLoss) {
            $catLoss = $items->sum('estimated_loss');
            $catCount = $items->sum('quantity');
            $percentage = $filteredLoss > 0 ? ($catLoss / $filteredLoss) * 100 : 0;

            return [
                'name' => $catName,
                'total_loss' => $catLoss,
                'total_qty' => $catCount,
                'percentage' => round($percentage, 1),
            ];
        })->sortByDesc('total_loss');

        $categories = Reject::getCategories();
        $baristas = Reject::getBaristas();
        $units = Reject::$units;

        return view('rejects.index', compact(
            'rejects',
            'dailyLoss',
            'dailyQty',
            'weeklyLoss',
            'weeklyQty',
            'monthlyLoss',
            'monthlyQty',
            'filteredLoss',
            'filteredQty',
            'categoryBreakdown',
            'categories',
            'baristas',
            'units',
            'startDate',
            'endDate',
            'category'
        ));
    }

    /**
     * Simpan Kategori Reject Baru
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:reject_categories,name',
        ]);

        RejectCategory::create([
            'name' => trim($validated['name']),
        ]);

        return redirect()->route('rejects.index')->with('success', 'Kategori reject baru "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Simpan Nama Barista Baru
     */
    public function storeBarista(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:baristas,name',
        ]);

        Barista::create([
            'name' => trim($validated['name']),
        ]);

        return redirect()->route('rejects.index')->with('success', 'Barista baru "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Simpan Data Bahan Reject Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'product_name' => 'required|string|max:255',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string',
            'estimated_loss' => 'required|numeric|min:0',
            'barista_name' => 'nullable|string|max:100',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        // Auto create category / barista if not existing
        RejectCategory::firstOrCreate(['name' => trim($validated['category'])]);

        if (!empty($validated['barista_name'])) {
            Barista::firstOrCreate(['name' => trim($validated['barista_name'])]);
        }

        Reject::create($validated);

        return redirect()->route('rejects.index')->with('success', 'Data bahan reject berhasil dicatat!');
    }

    /**
     * Update Data Bahan Reject
     */
    public function update(Request $request, Reject $reject)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'product_name' => 'required|string|max:255',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string',
            'estimated_loss' => 'required|numeric|min:0',
            'barista_name' => 'nullable|string|max:100',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        RejectCategory::firstOrCreate(['name' => trim($validated['category'])]);

        if (!empty($validated['barista_name'])) {
            Barista::firstOrCreate(['name' => trim($validated['barista_name'])]);
        }

        $reject->update($validated);

        return redirect()->route('rejects.index')->with('success', 'Data bahan reject berhasil diperbarui!');
    }

    /**
     * Hapus Data Bahan Reject
     */
    public function destroy(Reject $reject)
    {
        $reject->delete();

        return redirect()->route('rejects.index')->with('success', 'Data bahan reject berhasil dihapus!');
    }

    /**
     * Export PDF Laporan Bahan Reject
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');

        $query = Reject::query();

        if (!empty($startDate)) {
            $query->whereDate('date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('date', '<=', $endDate);
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        $rejects = (clone $query)->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $totalLoss = $rejects->sum('estimated_loss');
        $totalQty = $rejects->sum('quantity');

        $categoryBreakdown = $rejects->groupBy('category')->map(function ($items, $catName) use ($totalLoss) {
            $catLoss = $items->sum('estimated_loss');
            $catCount = $items->sum('quantity');
            $percentage = $totalLoss > 0 ? ($catLoss / $totalLoss) * 100 : 0;

            return [
                'name' => $catName,
                'total_loss' => $catLoss,
                'total_qty' => $catCount,
                'percentage' => round($percentage, 1),
            ];
        })->sortByDesc('total_loss');

        $pdfData = [
            'rejects' => $rejects,
            'totalLoss' => $totalLoss,
            'totalQty' => $totalQty,
            'categoryBreakdown' => $categoryBreakdown,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'category' => $category,
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
        ];

        $pdf = Pdf::loadView('rejects.pdf', $pdfData)->setPaper('a4', 'portrait');

        $filename = 'Laporan_Bahan_Reject_Calathea_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
