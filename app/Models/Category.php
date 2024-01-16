<?php

namespace App\Models;

use App\Enums\BaseItemStatus;
use Enigma\ValidatorTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Category extends Model
{
    use HasFactory, ValidatorTrait;

    protected $fillable = ['name', 'store_id', 'info_description', 'link'];

    /**
     * Boot method.
     */
    public static function boot(): void
    {
        parent::boot();

        // Added this method for validating the current model at model saving event
        static::validateOnSaving();
    }
//def get_items(sort_mode)
//sort_hash = Hash.new nil
//sort_hash["priority"] = "base_items.priority ASC"
//sort_hash["storehouse"] = "base_items.storehouse DESC"
//sort_hash["transport"] = "items.transport ASC"
//
//criteria = self.items.includes(:options, :base_item => [:description, :image, :producent]).where(:base_items => {:status => BaseItem::STATUSES[:active]})
//criteria = criteria.order(sort_hash[sort_mode] || "items."+sort_mode+" ASC") if BaseItem.exists_in_sort_modes?(sort_mode)
//    criteria.order("items.name ASC").to_a
//end

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('ordered', function (Builder $builder) {
            $builder->orderBy('name');
        });
    }

    public function getItems($sortMode): Collection
    {
        $appendSortModeCallable = match ($sortMode) {
            'priority' => fn($query) => $query->orderBy('base_items.priority'),
            'storehouse' => fn($query) => $query->orderByDesc('base_items.storehouse DESC'),
            'transport' => fn($query) => $query->orderBy('items.transport'),
            'price' => fn($query) => $query->orderBy('items.price'),
            default => fn($query) => $query->orderBy('items.name'),
        };

        $query = $this->items()
            ->with(['options', 'baseItem' => ['description', 'image', 'producent']])
            ->withWhereHas('baseItem', function ($query) {
                $query->where('status', '=', BaseItemStatus::ACTIVE);
            });

        $appendSortModeCallable($query);
        return $query->get();
    }

    public function validationRules(): array
    {
        return [
            'name' => 'required',
            'store_id' => 'required',
            'info_description' => 'nullable|max:255',
            'link' => 'required',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(CategoryLink::class);
    }

    public function parentLinks(): HasMany
    {
        return $this->hasMany(CategoryLink::class, 'category_id');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', '=', BaseItemStatus::ACTIVE);
    }

    public function scopeWithoutChildren(Builder $query): void
    {
        $query->where('children_count', '=', 0);
    }
}
