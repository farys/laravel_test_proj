<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseItemProducent extends Model
{
    use HasFactory;

    // Validation rules and attributes
    protected $fillable = [
        'name',
    ];
}
