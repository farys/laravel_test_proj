<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Filament\Traits\HasParentResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\CategoryResource\Pages;

class ViewCategory extends ViewRecord
{
    use HasParentResource;

    protected static string $resource = CategoryResource::class;
    protected string $relationshipKey = "store_id";

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->url(
                fn(Category $record): string => Pages\EditCategory::getParentResource()::getUrl('categories.edit', [
                    'parent' => $record->store_id,
                    'record' => $record->id,
                ])
            ),
        ];
    }
}
