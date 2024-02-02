<?php
namespace App\Filament\Resources\ImageResource\Inputs;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Get;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AttachmentImageFileUpload extends FileUpload
{
  public static function make(string $name): static
  {
    return parent::make($name)
      ->directory(config('images.product_image_path') . config('images.product_original_path_append'))
      ->image()
      ->imageEditor()
      ->getUploadedFileNameForStorageUsing(
        function (Get $get, TemporaryUploadedFile $file): string {
          return (string) \Str::slug($get('name')) . '.' . $file->guessExtension();
        }
      )
      ->acceptedFileTypes(['image/*']);
  }
}