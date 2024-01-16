<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OldLink extends Model
{
    use HasFactory;

    function destinationModel(): MorphTo{
        return  $this->morphTo(Model::class);
    }
}
