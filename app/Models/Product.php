<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;


class Product extends Model
{
    use HasFactory, Notifiable, softDeletes;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'url',
        'status',
        'user_id',

    ];

      public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
