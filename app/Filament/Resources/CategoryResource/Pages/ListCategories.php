<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Filament\Traits\HasParentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    use HasParentResource;
    
    protected static string $resource = CategoryResource::class;
    protected string $relationshipKey = "store_id";

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(
                    fn (): string => static::getParentResource()::getUrl('categories.create', [
                        'parent' => $this->parent,
                    ])
                ),
        ];
    }
}
