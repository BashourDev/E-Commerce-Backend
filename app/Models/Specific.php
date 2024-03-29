<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specific extends Model
{
    use HasFactory;

    protected $with = ['product', 'product.firstMediaOnly'];

    protected $fillable = ['color', 'colorText', 'size', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function soldItems()
    {
        return $this->hasMany(SoldItem::class, 'specific_id', 'id');
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, SoldItem::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_specific');
    }

}
