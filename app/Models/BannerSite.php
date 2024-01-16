<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BannerSite extends Model
{
    use HasFactory;

    public

    function store(): BelongsTo{
        return $this->belongsTo(Store::class);
    }

    function banners(): BelongsToMany{
        return $this->belongsToMany(Banner::class);
    }
}
