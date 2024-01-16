<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    function store(): BelongsTo{
        return $this->belongsTo(Store::class);
    }

    function offer(): BelongsTo{
        return $this->belongsTo(Offer::class);
    }

    function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    function itemsWarranties(): HasMany{
        return $this->hasMany(OrderItemWarranty::class);
    }
}
