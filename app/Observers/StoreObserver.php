<?php

namespace App\Observers;

use App\Models\Store;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreObserver
{
    protected Filesystem $storageDisk;

    function __construct()
    {
        $this->storageDisk = Storage::disk(config('filament.default_filesystem_disk'));
    }

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

        if (is_string($store->watermark_filename) && $this->storageDisk->exists($store->watermark_filename)) {
            $this->storageDisk->delete($store->watermark_filename);
        }
    }

}
