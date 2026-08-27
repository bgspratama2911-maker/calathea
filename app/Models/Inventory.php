<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    protected $fillable = [
        'item_code',
        'item_name',
        'category',
        'quantity',
        'unit',
        'condition',
        'purchase_date',
        'price',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Ambil Daftar Kategori dari Database (atau default jika kosong)
    public static function getCategories(): array
    {
        $dbCategories = InventoryCategory::orderBy('name', 'asc')->pluck('name')->toArray();
        if (count($dbCategories) > 0) {
            return $dbCategories;
        }

        return [
            'Peralatan & Mesin Bar',
            'Perlengkapan Seduh & Alat',
            'Bahan Baku & Stok',
            'Kemasan & Packaging',
            'Furniture & Interior',
            'Elektronik & AC',
            'Kebersihan & Sanitasi',
            'Lain-lain',
        ];
    }

    // Daftar Kondisi Barang
    public static array $conditions = [
        'Baik',
        'Rusak Ringan',
        'Rusak Berat',
        'Habis',
    ];

    // Daftar Satuan Barang
    public static array $units = [
        'Pcs',
        'Unit',
        'Pack',
        'Kg',
        'Gram',
        'Liter',
        'Botol',
        'Boks',
        'Roll',
    ];
}
