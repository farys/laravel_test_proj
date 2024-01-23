<?php

namespace App\Filament\Resources\ProducentResource\Pages;

use App\Filament\Resources\ProducentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducents extends ListRecords
{
    protected static string $resource = ProducentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
