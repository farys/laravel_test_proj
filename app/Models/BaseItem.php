<?php

namespace App\Models;

use App\Enums\BaseItemOrderingStatus;
use App\Enums\BaseItemStatus;
use App\Rules\CorrectOrderingStatus;
use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BaseItem extends Model
{
    use HasFactory, ValidatorTrait;

    const SORT_MODES = ["transport", "storehouse", "price", "name", "priority"];

    // Validation rules and attributes
    protected $fillable = [
        'name',
        'status',
        'weight',
        'short_description',
        'own_description',
        'storehouse',
        'synchro_storehouse',
        'ordering_status',
        'symbol',
        'producent_symbol',
        'ean_code',

        'producent_id',
    ];

    protected $casts = [
        'status' => BaseItemStatus::class,
        'ordering_status' => BaseItemOrderingStatus::class,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'storehouse' => 0,
    ];

    public static function validationRules(): array
    {
        return [
            'storehouse' => 'required|integer',
            'name' => 'required|unique:your_table_name', // Adjust the table name
            'weight' => 'nullable|numeric', // Validate only if transport button exists in item form
            'short_description' => 'nullable|max:255', // Maximum length of 255 characters
            'own_description' => 'nullable',
            'ordering_status' => ['required', 'integer', Rule::in(BaseItemOrderingStatus::valuesList()), new CorrectOrderingStatus()], // Validate against allowed values
        ];
    }

    function producent(): BelongsTo
    {
        return $this->belongsTo(BaseItemProducent::class);
    }

    function description(): BelongsTo
    {
        return $this->belongsTo(Description::class);
    }

    function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    function warranties(): HasMany
    {
        return $this->hasMany(Warranty::class)->orderBy("name");
    }

    function params(): HasMany
    {
        return $this->hasMany(BaseItemParam::class);
    }

    function displayParams(): HasMany
    {
        return $this->hasMany(BaseItemParam::class)->where("params", "=", true);
    }

    function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class);
    }

    function files(): BelongsToMany
    {
        return $this->belongsToMany(UploadedFile::class);
    }

    function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where("status", "=", BaseItemStatus::ACTIVE);
    }

    public function scopeHidden(Builder $query): void
    {
        $query->where("status", "=", BaseItemStatus::HIDDEN);
    }

    protected function symbol(): Attribute
    {
        return Attribute::make(
            //get: fn(string $value) => ucfirst($value),
            set: fn($value) => empty($value) ? null : $value,
        );
    }

    public static function boot()
    {
        parent::boot();
        static::saving(function () {
        });
    }

}
