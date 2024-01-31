<?php

namespace App\Observers;

use App\Models\Image as ImageModel;
use App\Models\Store;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageObserver
{

    protected Filesystem $storageDisk;

    public function __construct()
    {
        $this->storageDisk = Storage::disk(config('filament.default_filesystem_disk'));
    }
    /**
     * Handle the Image "created" event.
     */
    public function created(ImageModel $imageRecord) : void
    {
        $this->regenerateVariantsForOriginalImage($imageRecord);
    }

    /**
     * Handle the Image "updated" event.
     */
    public function updated(ImageModel $imageRecord) : void
    {
        $name_dirty = $imageRecord->isDirty('name');

        //rename image wariants filenames if the name of model has been changed
        if ($name_dirty) {
            $oldImageRecordAttrs = $imageRecord->getOriginal();

            $this->deleteImageWithVariants(
                $oldImageRecordAttrs['attachment_file_name'],
                $oldImageRecordAttrs['name'],
                true
            );
            $this->regenerateVariantsForOriginalImage($imageRecord);
        }

    }

    /**
     * Handle the Image "deleted" event.
     */
    public function deleted(ImageModel $imageRecord) : void
    {
        foreach ($imageRecord->representedBaseItems as $baseItem) {
            $baseItem->image()->dissociate();
            $baseItem->save();
        }

        $imageRecord->baseItems()->detach();

        $this->deleteImageWithVariants($imageRecord->attachment_file_name, $imageRecord->name);
    }

    /**
     * Handle the Image "restored" event.
     */
    public function restored(ImageModel $imageRecord) : void
    {
        $this->regenerateVariantsForOriginalImage($imageRecord);
    }

    /**
     * Handle the Image "force deleted" event.
     */
    public function forceDeleted(ImageModel $imageRecord) : void
    {
        $this->deleteImageWithVariants($imageRecord->attachment_file_name, $imageRecord->name);
    }

    protected function regenerateVariantsForOriginalImage(ImageModel $imageRecord)
    {
        $stores = Store::all();
        // create image manager with desired driver
        $manager = new ImageManager(new Driver());

        $image = $manager->read($this->storageDisk->get($imageRecord->attachment_file_name));
        $image->scale(width: config('images.thumbnail_scale'));

        $this->storageDisk->put(
            config('images.product_image_path')
            . config('images.product_thumbnail_path_append')
            . '/' . Str::slug($imageRecord->name)
            . ".png",
            $image->toPng()->toFilePointer()
        );

        foreach ($stores as $store) {
            $image = $manager->read($this->storageDisk->get($imageRecord->attachment_file_name));
            $image->scale(width: config('images.normal_scale'));
            $image->place($this->storageDisk->get($store->watermark_filename));
            $this->storageDisk->put(
                config('images.product_image_path')
                . '/' . Str::slug($store->domain)
                . '/' . Str::slug($imageRecord->name)
                . ".png",
                $image->toPng()->toFilePointer()
            );
        }
    }

    protected function deleteImageWithVariants(?string $imageAttachmentFilename, $imageRecordName, $leaveOriginalFile = false)
    {
        $stores = Store::all();
        $imageNameSlug = Str::slug($imageRecordName);
        $filesToDelete = [];

        if (! $leaveOriginalFile && is_string($imageAttachmentFilename)) {
            $filesToDelete[] = $imageAttachmentFilename;
        }

        //thumbnail
        $filesToDelete[] =
            config('images.product_image_path')
            . config('images.product_thumbnail_path_append')
            . '/' . $imageNameSlug
            . ".png";

        //each image per domain
        foreach ($stores as $store) {
            $filesToDelete[] = config('images.product_image_path')
                . '/' . Str::slug($store->domain)
                . '/' . $imageNameSlug
                . ".png";
        }

        foreach ($filesToDelete as $fileToDelete) {
            if (! is_string($fileToDelete) || ! $this->storageDisk->exists($fileToDelete)) {
                unset($fileToDelete);
            }
        }

        $this->storageDisk->delete($filesToDelete);
    }
}
