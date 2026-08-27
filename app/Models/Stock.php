<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'product_name',
        'category',
        'type', // 'raw_material' (Bahan Baku) or 'pos_menu' (Menu POS Kasir)
        'is_pos_item', // boolean
        'current_stock',
        'minimum_stock',
        'unit',
        'unit_price',
        'last_restock_date',
        'notes',
    ];

    protected $casts = [
        'last_restock_date' => 'date',
        'unit_price' => 'decimal:2',
        'current_stock' => 'integer',
        'minimum_stock' => 'integer',
        'is_pos_item' => 'boolean',
    ];

    // Status Stok Computasi
    public function getStatusAttribute(): string
    {
        if ($this->current_stock <= 0) {
            return 'Habis';
        }
        if ($this->current_stock <= $this->minimum_stock) {
            return 'Menipis';
        }
        return 'Aman';
    }

    // Ambil Daftar Kategori dari Database (atau default jika kosong)
    public static function getCategories(): array
    {
        $dbCategories = StockCategory::orderBy('name', 'asc')->pluck('name')->toArray();
        if (count($dbCategories) > 0) {
            return $dbCategories;
        }

        return [
            'Biji Kopi',
            'Susu & Creamer',
            'Powder & Bubuk',
            'Syrup & Perasa',
            'Cup & Packaging',
            'Pemanis & Lainnya',
        ];
    }

    // List Satuan Stok
    public static array $units = [
        'Kg',
        'Gram',
        'Liter',
        'Kaleng',
        'Pcs',
        'Pack',
        'Botol',
        'Boks',
        'Roll',
    ];

    // List Master Produk 19 Item Default (Urut Abjad A-Z)
    public static array $defaultProducts = [
        ['product_name' => 'Biji Kopi 70/30', 'category' => 'Biji Kopi', 'unit' => 'Kg', 'current_stock' => 10, 'minimum_stock' => 3, 'unit_price' => 120000],
        ['product_name' => 'Biji Kopi Natural', 'category' => 'Biji Kopi', 'unit' => 'Kg', 'current_stock' => 5, 'minimum_stock' => 2, 'unit_price' => 140000],
        ['product_name' => 'Biji Lumajang', 'category' => 'Biji Kopi', 'unit' => 'Kg', 'current_stock' => 6, 'minimum_stock' => 2, 'unit_price' => 135000],
        ['product_name' => 'Biji Wine Rancabali', 'category' => 'Biji Kopi', 'unit' => 'Kg', 'current_stock' => 4, 'minimum_stock' => 2, 'unit_price' => 180000],
        ['product_name' => 'Cup Dingin', 'category' => 'Cup & Packaging', 'unit' => 'Pack', 'current_stock' => 30, 'minimum_stock' => 5, 'unit_price' => 30000],
        ['product_name' => 'Cup Panas', 'category' => 'Cup & Packaging', 'unit' => 'Pack', 'current_stock' => 20, 'minimum_stock' => 5, 'unit_price' => 25000],
        ['product_name' => 'Gula Aren', 'category' => 'Pemanis & Lainnya', 'unit' => 'Liter', 'current_stock' => 12, 'minimum_stock' => 4, 'unit_price' => 35000],
        ['product_name' => 'Mix Creamer', 'category' => 'Susu & Creamer', 'unit' => 'Kg', 'current_stock' => 8, 'minimum_stock' => 2, 'unit_price' => 45000],
        ['product_name' => 'Monin Syrup Lychee', 'category' => 'Syrup & Perasa', 'unit' => 'Botol', 'current_stock' => 4, 'minimum_stock' => 1, 'unit_price' => 165000],
        ['product_name' => 'Monin Syrup Peach Tea', 'category' => 'Syrup & Perasa', 'unit' => 'Botol', 'current_stock' => 4, 'minimum_stock' => 1, 'unit_price' => 165000],
        ['product_name' => 'Monin Syrup Strawberry', 'category' => 'Syrup & Perasa', 'unit' => 'Botol', 'current_stock' => 4, 'minimum_stock' => 1, 'unit_price' => 165000],
        ['product_name' => 'Powder Chocolate', 'category' => 'Powder & Bubuk', 'unit' => 'Kg', 'current_stock' => 5, 'minimum_stock' => 2, 'unit_price' => 110000],
        ['product_name' => 'Powder Matcha', 'category' => 'Powder & Bubuk', 'unit' => 'Kg', 'current_stock' => 5, 'minimum_stock' => 2, 'unit_price' => 150000],
        ['product_name' => 'Sedotan', 'category' => 'Cup & Packaging', 'unit' => 'Pack', 'current_stock' => 15, 'minimum_stock' => 3, 'unit_price' => 15000],
        ['product_name' => 'Susu SKM', 'category' => 'Susu & Creamer', 'unit' => 'Kaleng', 'current_stock' => 15, 'minimum_stock' => 5, 'unit_price' => 12000],
        ['product_name' => 'Susu UHT', 'category' => 'Susu & Creamer', 'unit' => 'Liter', 'current_stock' => 24, 'minimum_stock' => 6, 'unit_price' => 18000],
        ['product_name' => 'Syrup DaVinci Caramel', 'category' => 'Syrup & Perasa', 'unit' => 'Botol', 'current_stock' => 3, 'minimum_stock' => 1, 'unit_price' => 145000],
        ['product_name' => 'Syrup DaVinci Hazelnut', 'category' => 'Syrup & Perasa', 'unit' => 'Botol', 'current_stock' => 3, 'minimum_stock' => 1, 'unit_price' => 145000],
        ['product_name' => 'Syrup DaVinci Vanilla', 'category' => 'Syrup & Perasa', 'unit' => 'Botol', 'current_stock' => 3, 'minimum_stock' => 1, 'unit_price' => 145000],
    ];
}
