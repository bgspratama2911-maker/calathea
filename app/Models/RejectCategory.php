<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectCategory extends Model
{
    use HasFactory;

    protected $table = 'reject_categories';

    protected $fillable = [
        'name',
    ];
}
