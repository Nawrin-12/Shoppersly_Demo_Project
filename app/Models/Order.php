<?php

namespace App\Models;
use App\Models\OrderImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $fillable = ['customer_name', 'customer_email', 'product_details', 'status'];

    public function images()
    {
        return $this->hasMany(OrderImage::class);
    }
    
}
