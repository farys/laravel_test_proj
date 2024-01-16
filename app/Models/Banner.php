<?php

namespace App\Models;

use App\Enums\BannerStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Banner extends Model
{
    // Validation rules
    public static function rules()
    {
        return [
            'name' => 'required|unique:banners',
            'destination_url' => 'nullable|url',
            'image' => 'required|image|mimes:png,jpg,jpeg',
            'store_id' => 'required',
            'link' => 'required',
        ];
    }

    // Update image filename before validation
    public static function boot()
    {
        parent::boot();

        static::updating(function ($banner) {
            if ($banner->isDirty('name')) {
                $banner->image->update(['filename' => $banner->link . '.' . $banner->image->extension]);
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function bannerPlaces(): BelongsToMany
    {
        return $this->belongsToMany(BannerPlace::class, 'banner_place_banner');
    }

    // Define an image relationship for handling file uploads

    public function bannerSites(): BelongsToMany
    {
        return $this->belongsToMany(BannerSite::class, 'banner_banner_site');
    }

    // Scope for filtering by site_ident

    public function image()
    {
        return $this->hasOne(BannerImage::class);
    }

    // Scope for filtering by place_ident

    public function scopeSite($query, string $siteIdent)
    {
        return $query->whereHas('bannerSites', function ($subQuery) use ($siteIdent) {
            $subQuery->where('ident', $siteIdent);
        });
    }

    // Scope for filtering active banners

    public function scopePlace($query, string $placeIdent)
    {
        return $query->whereHas('bannerPlaces', function ($subQuery) use ($placeIdent) {
            $subQuery->where('ident', $placeIdent);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', BannerStatus::ACTIVE);
    }


    // Mutator for generating the link based on the name
    public function link(): Attribute
    {
        return Attribute::make(get: fn(mixed $value, array $attributes) => Str::slug($attributes['name']));
    }
}
