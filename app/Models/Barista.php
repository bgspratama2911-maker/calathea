<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barista extends Model
{
    use HasFactory;

    protected $table = 'baristas';

    protected $fillable = [
        'name',
    ];
}
