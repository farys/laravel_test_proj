<?php
namespace App\Filament\Forms\Inputs;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;

class TextInputWithSlugAttached
{
  public static function make(string $name, string $slugFieldName): TextInput
  {
    return TextInput::make($name)
      ->live(onBlur: true)
      ->afterStateUpdated(function (Set $set, Get $get, ?string $state) use ($slugFieldName) {
        if (empty($get($slugFieldName)))
          $set($slugFieldName, \Str::slug($state));
      });
  }

}