<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ProductStatus;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'category', 'price', 'url', 'status'
    ];

    protected $casts = [
        'status' => ProductStatus::class,
    ];
}
