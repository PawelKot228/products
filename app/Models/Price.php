<?php

namespace App\Models;

use App\Enum\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'currency',
    ];

    protected $casts = [
        'currency' => Currency::class,
    ];
}
