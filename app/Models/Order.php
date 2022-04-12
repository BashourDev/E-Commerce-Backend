<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_SHIPPING = 1;
    const STATUS_DELIVERED = 2;

    protected $fillable = ['totalPrice', 'discount', 'currency', 'address', 'phone', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function soldItems()
    {
        return $this->hasMany(SoldItem::class, 'order_id', 'id');
    }

}
