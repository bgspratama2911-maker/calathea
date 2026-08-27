<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    protected $fillable = [
        'date',
        'category',
        'description',
        'payment_method',
        'amount',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Daftar Kategori Standar (Diurutkan Sesuai Abjad A-Z)
    public static array $categories = [
        'Air Amidis',
        'Belanja Biji Kopi',
        'Belanja Cup',
        'Belanja Pokok / Dapur',
        'Belanja Susu SKM',
        'Belanja Susu UHT',
        'Biji Kopi 70/30',
        'Biji Kopi V60',
        'Es Kristal',
        'Filter Manual',
        'Gula Aren',
        'Mix Creamer',
        'Operasional & Usaha',
        'Perlengkapan Cafe',
        'Plastik Cup',
        'Plastik Sampah',
        'Powder Chocolate',
        'Powder Matcha',
        'Sedotan',
        'Servis AC',
        'Syrup DaVinci Caramel',
        'Syrup DaVinci Hazelnut',
        'Syrup DaVinci Vanilla',
        'Syrup Monin Lychee',
        'Syrup Monin Peach Tea',
        'Syrup Monin Strawberry',
        'Tisu',
        'Token Listrik',
    ];

    // Daftar Metode Pembayaran Standar
    public static array $paymentMethods = [
        'Cash',
        'QRIS',
        'Transfer',
        'Kartu Debit/Kredit',
    ];
}
