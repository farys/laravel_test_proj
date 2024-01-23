<?php
namespace App\Filament\Resources\ProducentResource;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Get;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProducentImageFileUploadBuilder extends FileUpload
{
  public static function make(string $name): static
  {
    return parent::make($name)
      ->directory("uploads/producent_img")
      ->image()
      ->imageEditor()
      ->columns(1)
      ->getUploadedFileNameForStorageUsing(
        function (Get $get, TemporaryUploadedFile $file): string {
          return (string) \Str::slug($get('name')) . '.' . $file->guessExtension();
        }
      );
  }
}