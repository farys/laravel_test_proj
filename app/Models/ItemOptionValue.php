<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemOptionValue extends Model
{
    use HasFactory;

    function option(): BelongsTo{
        return $this->belongsTo(ItemOption::class);
    }

    function item(): BelongsTo{
        return $this->belongsTo(Item::class);
    }
}
