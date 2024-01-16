<?php

namespace App\Enums;

use App\Filament\Traits\AsExtendedEnum;
use Filament\Support\Contracts\HasLabel;

enum BaseItemStatus: int implements HasLabel
{

    use AsExtendedEnum;
    case ACTIVE = 0;
    case HIDDEN = 1;

    function getLabel(): ?string
    {
        return match($this){
            self::ACTIVE => __('attributes.status.active'),
            self::HIDDEN => __('attributes.status.hidden'),
        };
    }
}

