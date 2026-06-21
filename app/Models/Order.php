<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'total_amount', 'status_id',
        'name', 'phone', 'email', 'address', 'city', 'postal_code',
        'delivery_method', 'payment_method', 'payment_status', 'comment'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
            $status = OrderStatus::where('name', 'Новый')->first();
            $order->status_id = $status ? $status->id : 1;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
