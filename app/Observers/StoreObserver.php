<?php

namespace App\Observers;

use App\Models\Store;
use Illuminate\Support\Facades\Storage;

class StoreObserver
{

    /**
     * Handle the Store "deleted" event.
     */
    public function deleted(Store $store) : void
    {

        foreach ($store->items as $item) {
            $item->delete();
        }

        foreach ($store->categories as $category) {
            $category->delete();
        }

        $storageDisk = Storage::disk(config('filament.default_filesystem_disk'));

        if (is_string($store->watermark_filename) && $storageDisk->exists($store->watermark_filename)) {
            $storageDisk->delete($store->watermark_filename);
        }
    }

}
