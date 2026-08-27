<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryController extends Controller
{
    /**
     * Tampilkan Halaman Inventaris Barang
     */
    public function index(Request $request)
    {
        // Seed default categories if empty
        if (InventoryCategory::count() === 0) {
            $defaultCategories = [
                'Peralatan & Mesin Bar',
                'Perlengkapan Seduh & Alat',
                'Bahan Baku & Stok',
                'Kemasan & Packaging',
                'Furniture & Interior',
                'Elektronik & AC',
                'Kebersihan & Sanitasi',
                'Lain-lain',
            ];
            foreach ($defaultCategories as $catName) {
                InventoryCategory::firstOrCreate(['name' => $catName]);
            }
        }

        $search = $request->input('search');
        $category = $request->input('category');
        $condition = $request->input('condition');

        $query = Inventory::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        if (!empty($condition)) {
            $query->where('condition', $condition);
        }

        $inventories = (clone $query)->orderBy('created_at', 'desc')->get();

        // Perhitungan KPI summary
        $totalItems = $inventories->count();
        $totalQuantity = $inventories->sum('quantity');
        $totalValue = $inventories->sum(function($item) {
            return $item->quantity * $item->price;
        });
        $goodConditionCount = $inventories->where('condition', 'Baik')->count();
        $damagedCount = $inventories->whereIn('condition', ['Rusak Ringan', 'Rusak Berat'])->count();

        $categories = Inventory::getCategories();
        $conditions = Inventory::$conditions;
        $units = Inventory::$units;

        return view('inventories.index', compact(
            'inventories',
            'totalItems',
            'totalQuantity',
            'totalValue',
            'goodConditionCount',
            'damagedCount',
            'categories',
            'conditions',
            'units',
            'search',
            'category',
            'condition'
        ));
    }

    /**
     * Simpan Kategori Inventaris Baru
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:inventory_categories,name',
        ]);

        InventoryCategory::create([
            'name' => trim($validated['name']),
        ]);

        return redirect()->route('inventories.index')->with('success', 'Kategori inventaris baru "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Simpan Data Inventaris Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => 'nullable|string|max:50|unique:inventories,item_code',
            'item_name' => 'required|string|max:255',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|in:' . implode(',', Inventory::$units),
            'condition' => 'required|string|in:' . implode(',', Inventory::$conditions),
            'purchase_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        // Auto add category if not exists
        InventoryCategory::firstOrCreate(['name' => trim($validated['category'])]);

        // Auto Generate Item Code jika kosong
        if (empty($validated['item_code'])) {
            $nextId = (Inventory::max('id') ?? 0) + 1;
            $validated['item_code'] = 'INV-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }

        Inventory::create($validated);

        return redirect()->route('inventories.index')->with('success', 'Data barang inventaris berhasil ditambahkan!');
    }

    /**
     * Update Data Inventaris
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_code' => 'required|string|max:50|unique:inventories,item_code,' . $inventory->id,
            'item_name' => 'required|string|max:255',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|in:' . implode(',', Inventory::$units),
            'condition' => 'required|string|in:' . implode(',', Inventory::$conditions),
            'purchase_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        // Auto add category if not exists
        InventoryCategory::firstOrCreate(['name' => trim($validated['category'])]);

        $inventory->update($validated);

        return redirect()->route('inventories.index')->with('success', 'Data barang inventaris berhasil diperbarui!');
    }

    /**
     * Hapus Data Inventaris
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventories.index')->with('success', 'Data barang inventaris berhasil dihapus!');
    }

    /**
     * Export PDF Inventaris Barang
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $condition = $request->input('condition');

        $query = Inventory::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        if (!empty($condition)) {
            $query->where('condition', $condition);
        }

        $inventories = (clone $query)->orderBy('item_code', 'asc')->get();

        $totalItems = $inventories->count();
        $totalQuantity = $inventories->sum('quantity');
        $totalValue = $inventories->sum(function($item) {
            return $item->quantity * $item->price;
        });

        $pdfData = [
            'inventories' => $inventories,
            'totalItems' => $totalItems,
            'totalQuantity' => $totalQuantity,
            'totalValue' => $totalValue,
            'search' => $search,
            'category' => $category,
            'condition' => $condition,
            'generatedAt' => now()->translatedFormat('d F Y H:i'),
        ];

        $pdf = Pdf::loadView('inventories.pdf', $pdfData)->setPaper('a4', 'landscape');

        $filename = 'Laporan_Inventaris_Calathea_' . date('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
