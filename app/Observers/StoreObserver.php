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

        foreach ($store->items as  $item) {
            $item->delete();
        }

        foreach ($store->categories as $category) {
            $category->delete();
        }
        
        $storageDisk = Storage::disk(config('filament.default_filesystem_disk'));
        $storageDisk->delete($store->watermark_filename);
    }

}
