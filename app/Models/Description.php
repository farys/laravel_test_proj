<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Description extends Model
{
    use HasFactory;

    function baseItems(): HasMany{
        return $this->hasMany(BaseItem::class);
    }
}
