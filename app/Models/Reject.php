<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reject extends Model
{
    use HasFactory;

    protected $table = 'rejects';

    protected $fillable = [
        'date',
        'product_name',
        'category',
        'quantity',
        'unit',
        'estimated_loss',
        'reason',
        'notes',
        'barista_name',
    ];

    protected $casts = [
        'date' => 'date',
        'estimated_loss' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Ambil Kategori Reject dari Database (atau default jika kosong)
    public static function getCategories(): array
    {
        $dbCategories = RejectCategory::orderBy('name', 'asc')->pluck('name')->toArray();
        if (count($dbCategories) > 0) {
            return $dbCategories;
        }

        return [
            'Bahan Baku Kadaluarsa',
            'Kesalahan Seduh / Human Error',
            'Kemasan / Cup Rusak',
            'Susu Basi / Tumpah',
            'Biji Kopi Gosong / Rusak',
            'Lain-lain',
        ];
    }

    // Ambil Daftar Barista dari Database
    public static function getBaristas(): array
    {
        return Barista::orderBy('name', 'asc')->pluck('name')->toArray();
    }

    // List Satuan
    public static array $units = [
        'Pcs',
        'Cup',
        'Botol',
        'Kg',
        'Gram',
        'Liter',
        'Pack',
        'Kaleng',
    ];
}
