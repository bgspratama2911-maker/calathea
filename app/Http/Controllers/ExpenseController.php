<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Tampilkan Halaman Utama / Dashboard dengan Filter, KPI, Ringkasan Kategori & Grafik (Harian, Bulanan, Tahunan)
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');

        // Query Utama dengan Filter
        $query = Expense::query();

        if (!empty($startDate)) {
            $query->whereDate('date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('date', '<=', $endDate);
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        // Ambil Data Terfilter (Ordered by date desc, id desc)
        $expenses = (clone $query)->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        // 1. Perhitungan KPI Summary Cards
        $totalExpenses = $expenses->sum('amount');
        $totalTransactions = $expenses->count();
        $averagePerTransaction = $totalTransactions > 0 ? $totalExpenses / $totalTransactions : 0;

        // KPI Pengeluaran Terbesar
        $highestExpenseItem = $expenses->sortByDesc('amount')->first();
        $highestExpense = $highestExpenseItem ? $highestExpenseItem->amount : 0;
        $highestExpenseDesc = $highestExpenseItem ? $highestExpenseItem->description . ' (' . $highestExpenseItem->category . ')' : '-';

        // 2. Perhitungan Tabel Ringkasan per Kategori
        $categoryBreakdown = $expenses->groupBy('category')->map(function ($items, $catName) use ($totalExpenses) {
            $catTotal = $items->sum('amount');
            $catCount = $items->count();
            $percentage = $totalExpenses > 0 ? ($catTotal / $totalExpenses) * 100 : 0;

            return [
                'name' => $catName,
                'total' => $catTotal,
                'count' => $catCount,
                'percentage' => round($percentage, 1),
            ];
        })->sortByDesc('total');

        // 3. Data Grafik Harian (Daily Chart)
        $dailyGrouped = $expenses->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m-d');
        })->map(function ($items) {
            return $items->sum('amount');
        })->sortKeys();

        $dailyChartLabels = [];
        $dailyChartData = [];
        foreach ($dailyGrouped as $dateStr => $sumAmount) {
            $dailyChartLabels[] = Carbon::parse($dateStr)->format('d M Y');
            $dailyChartData[] = (float) $sumAmount;
        }

        // 4. Data Grafik Bulanan (Monthly Chart)
        $monthlyGrouped = $expenses->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        })->map(function ($items) {
            return $items->sum('amount');
        })->sortKeys();

        $monthlyChartLabels = [];
        $monthlyChartData = [];
        foreach ($monthlyGrouped as $yearMonth => $sumAmount) {
            $monthlyChartLabels[] = Carbon::createFromFormat('Y-m', $yearMonth)->translatedFormat('M Y');
            $monthlyChartData[] = (float) $sumAmount;
        }

        // 5. Data Grafik Tahunan (Yearly Chart)
        $yearlyGrouped = $expenses->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y');
        })->map(function ($items) {
            return $items->sum('amount');
        })->sortKeys();

        $yearlyChartLabels = [];
        $yearlyChartData = [];
        foreach ($yearlyGrouped as $year => $sumAmount) {
            $yearlyChartLabels[] = 'Tahun ' . $year;
            $yearlyChartData[] = (float) $sumAmount;
        }

        // 6. Data Analisa Finansial Tambahan (Month-To-Date & Proyeksi Run-Rate)
        $now = Carbon::now();
        $currentMonthExpenses = Expense::whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->sum('amount');

        $daysPassedInMonth = max(1, (int) $now->format('j'));
        $totalDaysInMonth = (int) $now->daysInMonth;
        $dailyAverageThisMonth = $currentMonthExpenses > 0 ? $currentMonthExpenses / $daysPassedInMonth : 0;
        $projectedMonthEndExpenses = $dailyAverageThisMonth * $totalDaysInMonth;

        // Payment Method Breakdown
        $paymentBreakdown = $expenses->groupBy('payment_method')->map(function ($items, $pmName) use ($totalExpenses) {
            $pmTotal = $items->sum('amount');
            $pmCount = $items->count();
            $percentage = $totalExpenses > 0 ? ($pmTotal / $totalExpenses) * 100 : 0;

            return [
                'name' => $pmName,
                'total' => $pmTotal,
                'count' => $pmCount,
                'percentage' => round($percentage, 1),
            ];
        })->sortByDesc('total');

        // Ambil & urutkan kategori alfabetis A-Z
        $categories = Expense::$categories;
        sort($categories);

        $paymentMethods = Expense::$paymentMethods;

        return view('expenses.index', compact(
            'expenses',
            'totalExpenses',
            'totalTransactions',
            'averagePerTransaction',
            'highestExpense',
            'highestExpenseDesc',
            'categoryBreakdown',
            'categories',
            'paymentMethods',
            'startDate',
            'endDate',
            'category',
            'dailyChartLabels',
            'dailyChartData',
            'monthlyChartLabels',
            'monthlyChartData',
            'yearlyChartLabels',
            'yearlyChartData',
            'currentMonthExpenses',
            'daysPassedInMonth',
            'totalDaysInMonth',
            'dailyAverageThisMonth',
            'projectedMonthEndExpenses',
            'paymentBreakdown'
        ));
    }

    /**
     * Simpan Pengeluaran Baru
     */
    public function store(Request $request)
    {
        $categories = Expense::$categories;

        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|in:' . implode(',', $categories),
            'description' => 'required|string|max:255',
            'payment_method' => 'required|string|in:' . implode(',', Expense::$paymentMethods),
            'amount' => 'required|numeric|min:1',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran berhasil ditambahkan!');
    }

    /**
     * Update Pengeluaran
     */
    public function update(Request $request, Expense $expense)
    {
        $categories = Expense::$categories;

        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|in:' . implode(',', $categories),
            'description' => 'required|string|max:255',
            'payment_method' => 'required|string|in:' . implode(',', Expense::$paymentMethods),
            'amount' => 'required|numeric|min:1',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    /**
     * Hapus Pengeluaran
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran berhasil dihapus!');
    }

    /**
     * Export PDF Dokumen Laporan Pengeluaran Sesuai Filter
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');

        $query = Expense::query();

        if (!empty($startDate)) {
            $query->whereDate('date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('date', '<=', $endDate);
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        $expenses = (clone $query)->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $totalExpenses = $expenses->sum('amount');
        $totalTransactions = $expenses->count();
        $averagePerTransaction = $totalTransactions > 0 ? $totalExpenses / $totalTransactions : 0;

        $highestExpenseItem = $expenses->sortByDesc('amount')->first();
        $highestExpense = $highestExpenseItem ? $highestExpenseItem->amount : 0;
        $highestExpenseDesc = $highestExpenseItem ? $highestExpenseItem->description . ' (' . $highestExpenseItem->category . ')' : '-';

        $categoryBreakdown = $expenses->groupBy('category')->map(function ($items, $catName) use ($totalExpenses) {
            $catTotal = $items->sum('amount');
            $catCount = $items->count();
            $percentage = $totalExpenses > 0 ? ($catTotal / $totalExpenses) * 100 : 0;

            return [
                'name' => $catName,
                'total' => $catTotal,
                'count' => $catCount,
                'percentage' => round($percentage, 1),
            ];
        })->sortByDesc('total');

        $pdfData = [
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
            'totalTransactions' => $totalTransactions,
            'averagePerTransaction' => $averagePerTransaction,
            'highestExpense' => $highestExpense,
            'highestExpenseDesc' => $highestExpenseDesc,
            'categoryBreakdown' => $categoryBreakdown,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'category' => $category,
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
        ];

        $pdf = Pdf::loadView('expenses.pdf', $pdfData)->setPaper('a4', 'portrait');

        $filename = 'Laporan_Pengeluaran_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
