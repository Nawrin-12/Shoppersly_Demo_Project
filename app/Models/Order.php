<?php

namespace App\Models;
use App\Models\OrderImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Order extends Model
{
    protected $fillable = ['customer_name', 'customer_email', 'product_details'];

    public function images()
    {
        return $this->hasMany(OrderImage::class);
    }

    public function product(): belongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }


}
