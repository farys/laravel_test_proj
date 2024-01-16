<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemWarranty extends Model
{
    use HasFactory;

    function order(): BelongsTo{
        return $this->belongsTo(Order::class);
    }

    function orderItem(): BelongsTo{
        return $this->belongsTo(OrderItem::class);
    }
}
