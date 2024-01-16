<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UploadedFile extends Model
{
    use HasFactory;

    function baseItems(): BelongsToMany{
        return $this->belongsToMany(BaseItem::class);
    }
}
