<?php

namespace App\Enums;
use App\Filament\Traits\AsExtendedEnum;
use Filament\Support\Contracts\HasLabel;

enum BaseItemOrderingStatus: int implements HasLabel
{
    use AsExtendedEnum;
    case TO_ORDER = 0;
    case ORDERED = 1;
    case OUT_OF_SALE = 2;
    case NONE = 3;

    function getLabel(): ?string
    {
        return match($this){
            self::TO_ORDER => __('attributes.ordering_status.to_order'),
            self::ORDERED => __('attributes.ordering_status.ordered'),
            self::OUT_OF_SALE => __('attributes.ordering_status.out_of_sale'),
            self::NONE => __('attributes.ordering_status.none'),
        };
    }
}

