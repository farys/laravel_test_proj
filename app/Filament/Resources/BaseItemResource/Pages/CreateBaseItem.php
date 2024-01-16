<?php

namespace App\Filament\Resources\BaseItemResource\Pages;

use App\Filament\Resources\BaseItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBaseItem extends CreateRecord
{
    protected static string $resource = BaseItemResource::class;
}
