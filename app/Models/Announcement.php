<?php

namespace App\Models;

use App\Enums\StandardActiveStatus;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use HasFactory;

    function __toString(): string
    {
        return $this->title;
    }

    // Validation rules
    public static function rules()
    {
        return [
            'title' => 'required',
            'body' => 'required',
            'link' => 'required|unique',
            'store_id' => 'required',
            'created_at' => 'required',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where("active", StandardActiveStatus::ACTIVE);
    }

    function link(): string
    {
        return Str::slug($this->title);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('order_id_desc', function (Builder $builder) {
            $builder->orderByDesc("id");
        });
    }

    protected $fillable = ['title', 'body', 'link'];

    protected $attributes = [
        "status" => StandardActiveStatus::ACTIVE,
        'created_at' => DateTime::now(),
    ];
}
