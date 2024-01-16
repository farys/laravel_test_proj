<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'baseItem_id',
        'link',
        'title',
        'store_id',
        'base_item_id',
    ];

    protected $casts = [
    ];

    public static function rules()
    {
        return [
            'name' => 'required',
            'baseItem_id' => 'required',
            'store_id' => 'required',
            'link' => ['alpha_dash', 'required'],
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function baseItem(): BelongsTo
    {
        return $this->belongsTo(BaseItem::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(ItemOption::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function link(): Attribute
    {
        return Attribute::make(get: fn(mixed $value, array $attributes) => Str::slug($attributes['name']));
    }
}
