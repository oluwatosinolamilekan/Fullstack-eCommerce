<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = ['customer_name', 'customer_email','status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected $casts = [
        'status' => OrderStatus::class,
    ];
}
