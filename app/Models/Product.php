<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function images(): HasMany{
        return  $this->hasMany(ProductImage::class);
    }
    public function user(): BelongsTo{
        return  $this->belongsTo(User::class);
    }
}
