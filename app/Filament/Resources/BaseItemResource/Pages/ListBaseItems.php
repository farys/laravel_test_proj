<?php

namespace App\Filament\Resources\BaseItemResource\Pages;

use App\Filament\Resources\BaseItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBaseItems extends ListRecords
{
    protected static string $resource = BaseItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
