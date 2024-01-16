<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    use HasFactory;

    function store(): BelongsTo{
        return $this->belongsTo(Store::class);
    }

    function courier(): BelongsTo{
        return $this->belongsTo(OfferCourier::class);
    }

    function offerItems(): HasMany{
        return $this->hasMany(OfferItem::class);
    }

    function offerCouriers(): HasMany{
        return $this->hasMany(OfferCourier::class);
    }


}
