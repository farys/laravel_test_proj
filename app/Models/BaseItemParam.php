<?php

namespace App\Models;

use App\Enums\BaseItemParamStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class BaseItemParam extends Model
{
    use HasFactory;

    function baseItem(): BelongsTo{
        return $this->belongsTo(BaseItem::class);
    }

    function fullRawRecord(): string{
        return $this->name . " " . $this->value . " " . $this->ext;
    }

    function scopeActive(Builder $query): void{
        $query->where("status", "=", BaseItemParamStatus::ACTIVE);
    }
}
