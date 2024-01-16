<?php

namespace App\Filament\Traits;

trait AsExtendedEnum
{
  public static function valuesList(): array
  {
    return array_column(self::cases(), "value");
  }

  public static function namesList()
  {
    return array_column(self::cases(), "name");
  }

  public static function valueToNameLookup(callable|null $mapCallback = null)
  {
    $result = array_column(self::cases(), 'name', 'value');
    if ($mapCallback !== null) {
      $result = array_map($mapCallback, $result);
    }

    return $result;
  }

}
