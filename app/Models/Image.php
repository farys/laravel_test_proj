<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'attachment_file_name',
        'baseItems',
        'representedBaseItems',
    ];

    protected $casts = [
    ];

    function baseItems(): BelongsToMany{
        return $this->belongsToMany(BaseItem::class);
    }

    function representedBaseItems(): HasMany{
        return $this->hasMany(BaseItem::class, 'image_id');
    }
}
