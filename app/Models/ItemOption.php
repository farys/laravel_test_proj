<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemOption extends Model
{
    use HasFactory;

    function store(): BelongsTo{
        return $this->belongsTo(Store::class);
    }

    function values(): HasMany{
        return $this->hasMany(ItemOptionValue::class);
    }

    function items(): BelongsToMany{
        return $this->belongsToMany(Item::class);
    }

}
