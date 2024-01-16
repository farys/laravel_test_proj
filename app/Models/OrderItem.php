<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    function order(): BelongsTo{
        return $this->belongsTo(Order::class);
    }

    function parentOrderItem(): BelongsTo{
        return $this->belongsTo(OrderItem::class);
    }

    function children(): HasMany{
        return $this->hasMany(OrderItem::class, foreignKey: "parent_order_item_id");
    }

    function warranties(): HasMany{
        return $this->hasMany(OrderItemWarranty::class);
    }
}
