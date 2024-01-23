<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    const DOMAIN_REGEX = '/^[a-zA-Z0-9.-]+$/';
    
    public static function rules()
    {
        return [
            'domain' => 'required|regex:' . Store::DOMAIN_REGEX,
            'watermark_filename' => 'required',
            'login' => 'required',
            'company_name' => 'required',
            'site_title' => 'required',
        ];
    }

    function categories(): HasMany
    {
        return $this->hasMany(Category::class)->where('parent_id', '=', null);
    }

    function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    function options(): HasMany
    {
        return $this->hasMany(ItemOption::class);
    }

    function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    function subpages(): HasMany
    {
        return $this->hasMany(Subpage::class);
    }

    function bannerPlaces(): HasMany
    {
        return $this->hasMany(BannerPlace::class);
    }

    function bannerSites(): HasMany
    {
        return $this->hasMany(BannerSite::class);
    }

    function banners(): HasMany
    {
        return $this->hasMany(Banner::class);
    }

    function oldLinks(): HasMany
    {
        return $this->hasMany(OldLink::class);
    }

    function collections(): HasMany
    {
        return $this->hasMany(StoreCollection::class);
    }

    protected $fillable = ['domain', 'login', 'company_name', 'site_title', 'site_address', 'account_number', 'account_receiver', 'account_bank_name', 'watermark_filename'];
}
