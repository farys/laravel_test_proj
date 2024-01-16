<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Filament\Traits\HasParentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    use HasParentResource;
    protected static string $resource = CategoryResource::class;
    protected string $relationshipKey = "store_id";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? static::getParentResource()::getUrl('categories.index', [
            'parent' => $this->parent,
        ]);
    }
 
    protected function configureDeleteAction(Actions\DeleteAction $action): void
    {
        $resource = static::getResource();
 
        $action->authorize($resource::canDelete($this->getRecord()))
            ->successRedirectUrl(static::getParentResource()::getUrl('categories.index', [
                'parent' => $this->parent,
            ]));
    }
}
