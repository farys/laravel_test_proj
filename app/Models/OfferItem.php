<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfferItem extends Model
{
    use HasFactory;

    function offer(): BelongsTo{
        return $this->belongsTo(Offer::class);
    }

    function item(): BelongsTo{
        return $this->belongsTo(Item::class);
    }

    function option(): BelongsTo{
        return $this->belongsTo(ItemOption::class);
    }

    function optionValue(): BelongsTo{
        return $this->belongsTo(ItemOptionValue::class);
    }

    function parentOfferItem(): BelongsTo{
        return $this->belongsTo(OfferItem::class);
    }

    function children(): HasMany{
        return $this->hasMany(OfferItem::class, foreignKey: "parent_offer_item_id");
    }
}
