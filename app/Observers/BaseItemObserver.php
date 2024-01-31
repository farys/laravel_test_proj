<?php

namespace App\Observers;

use App\Models\BaseItem;
use Illuminate\Support\Facades\Storage;

class BaseItemObserver
{
    /**
     * Handle the BaseItem "created" event.
     */
    public function created(BaseItem $baseItem) : void
    {
        //
    }

    /**
     * Handle the BaseItem "updated" event.
     */
    public function updated(BaseItem $baseItem) : void
    {
        //
    }

    /**
     * Handle the BaseItem "deleted" event.
     */
    public function deleted(BaseItem $baseItem) : void
    {
        foreach ($baseItem->items as $item) {
            $item->delete();
        }
        
        foreach ($baseItem->images as $image) {
            $image->detach();
        }

        foreach ($baseItem->files as $file) {
            $file->detach();
        }

        foreach ($baseItem->warranties as $warrant) {
            $warrant->delete();
        }

        foreach ($baseItem->params as $param) {
            $param->delete();
        }
    }

    /**
     * Handle the BaseItem "restored" event.
     */
    public function restored(BaseItem $baseItem) : void
    {
        //
    }

    /**
     * Handle the BaseItem "force deleted" event.
     */
    public function forceDeleted(BaseItem $baseItem) : void
    {
        //
    }
}
