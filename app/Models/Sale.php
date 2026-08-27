<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'date',
        'product_name',
        'category',
        'quantity_sold',
        'price_per_unit',
        'total_income',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'price_per_unit' => 'decimal:2',
        'total_income' => 'decimal:2',
        'quantity_sold' => 'integer',
    ];

    // Ambil Daftar Kategori Penjualan dari Database (atau default jika kosong)
    public static function getCategories(): array
    {
        $dbCategories = SaleCategory::orderBy('name', 'asc')->pluck('name')->toArray();
        if (count($dbCategories) > 0) {
            return $dbCategories;
        }

        return [
            'Espresso Based',
            'Coffee Specialty',
            'Flavored Coffee',
            'Non-Coffee & Tea',
            'Botolan / Takeaway',
        ];
    }

    // List Metode Pembayaran
    public static array $paymentMethods = [
        'QRIS',
        'Cash',
        'Transfer',
        'Kartu Debit/Kredit',
    ];

    // List 25 Master Produk Kopi & Minuman Calathea Coffee (Urut Abjad A-Z)
    public static array $defaultProducts = [
        ['name' => 'Americano', 'category' => 'Espresso Based', 'default_price' => 18000],
        ['name' => 'Calathea', 'category' => 'Coffee Specialty', 'default_price' => 25000],
        ['name' => 'Calathea Botolan', 'category' => 'Botolan / Takeaway', 'default_price' => 50000],
        ['name' => 'Cappucino', 'category' => 'Espresso Based', 'default_price' => 22000],
        ['name' => 'Caramel Coffe', 'category' => 'Flavored Coffee', 'default_price' => 25000],
        ['name' => 'Chocolate Strawberry', 'category' => 'Non-Coffee & Tea', 'default_price' => 24000],
        ['name' => 'Cloud Cream', 'category' => 'Non-Coffee & Tea', 'default_price' => 23000],
        ['name' => 'Cold Brew', 'category' => 'Coffee Specialty', 'default_price' => 23000],
        ['name' => 'Cold Brew Botolan', 'category' => 'Botolan / Takeaway', 'default_price' => 45000],
        ['name' => 'Creamson', 'category' => 'Coffee Specialty', 'default_price' => 24000],
        ['name' => 'Creamson Botolan', 'category' => 'Botolan / Takeaway', 'default_price' => 48000],
        ['name' => 'Espresso', 'category' => 'Espresso Based', 'default_price' => 15000],
        ['name' => 'Hazelnut Coffe', 'category' => 'Flavored Coffee', 'default_price' => 25000],
        ['name' => 'Lychee Tea', 'category' => 'Non-Coffee & Tea', 'default_price' => 20000],
        ['name' => 'Magic', 'category' => 'Espresso Based', 'default_price' => 23000],
        ['name' => 'Matcha Java', 'category' => 'Non-Coffee & Tea', 'default_price' => 23000],
        ['name' => 'Matcha Latte', 'category' => 'Non-Coffee & Tea', 'default_price' => 22000],
        ['name' => 'Matcha Strawberry', 'category' => 'Non-Coffee & Tea', 'default_price' => 24000],
        ['name' => 'Mocha', 'category' => 'Flavored Coffee', 'default_price' => 24000],
        ['name' => 'Peach Tea', 'category' => 'Non-Coffee & Tea', 'default_price' => 20000],
        ['name' => 'Sanger', 'category' => 'Coffee Specialty', 'default_price' => 20000],
        ['name' => 'Sanger Botolan', 'category' => 'Botolan / Takeaway', 'default_price' => 40000],
        ['name' => 'Signature Chocolate', 'category' => 'Non-Coffee & Tea', 'default_price' => 23000],
        ['name' => 'Strawberry Tea', 'category' => 'Non-Coffee & Tea', 'default_price' => 20000],
        ['name' => 'Vanilla Coffe', 'category' => 'Flavored Coffee', 'default_price' => 25000],
    ];

    /**
     * Ambil Nama Semua Produk Urut Abjad
     */
    public static function getProductNames(): array
    {
        return array_column(self::$defaultProducts, 'name');
    }
}
