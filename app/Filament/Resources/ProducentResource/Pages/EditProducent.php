<?php

namespace App\Filament\Resources\ProducentResource\Pages;

use App\Filament\Resources\ProducentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProducent extends EditRecord
{
    protected static string $resource = ProducentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
