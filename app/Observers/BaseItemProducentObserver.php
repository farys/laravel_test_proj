<?php

namespace App\Observers;

use App\Models\BaseItemProducent;
use Illuminate\Support\Facades\Storage;

class BaseItemProducentObserver
{
    /**
     * Handle the Producent "deleted" event.
     */
    public function deleted(BaseItemProducent $producent) : void
    {
        foreach ($producent->baseItems as $baseItem) {
            $baseItem->delete();
        }

        $storageDisk = Storage::disk(config('filament.default_filesystem_disk'));
        $storageDisk->delete($producent->image_file_name);
    }
}
