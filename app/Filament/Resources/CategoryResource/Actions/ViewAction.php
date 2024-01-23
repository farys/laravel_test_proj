<?php

namespace App\Filament\Resources\CategoryResource\Actions;

use App\Models\Category;
use Filament\Tables\Actions;
use App\Filament\Resources\CategoryResource\Pages;

class ViewAction extends Actions\ViewAction
{
  public static function make(?string $name = null): static
  {
    return parent::make($name)->url(
      fn(Category $record): string => Pages\ViewCategory::getParentResource()::getUrl('categories.view', [
        'parent' => $record->store_id,
        'record' => $record->id,
      ])
    );
  }


}