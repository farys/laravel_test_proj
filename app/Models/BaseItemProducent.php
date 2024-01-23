<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BaseItemProducent extends Model
{
    use HasFactory;

    // Validation rules and attributes
    protected $fillable = [
        'name',
        'link',
        'image_file_name',
        'title',
        'min_delivery_days',
        'max_delivery_days',
    ];

    public function baseItems(): HasMany
    {
        return $this->hasMany(BaseItem::class, 'producent_id');
    }
}
