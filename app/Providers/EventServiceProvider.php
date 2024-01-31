<?php

namespace App\Providers;

use App\Models\BaseItem;
use App\Models\BaseItemProducent;
use App\Models\Category;
use App\Models\Image;
use App\Models\Store;
use App\Observers\BaseItemObserver;
use App\Observers\BaseItemProducentObserver;
use App\Observers\CategoryObserver;
use App\Observers\ImageObserver;
use App\Observers\StoreObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Category::class => [CategoryObserver::class],
        BaseItem::class => [BaseItemObserver::class],
        Image::class => [ImageObserver::class],
        Store::class => [StoreObserver::class],
        BaseItemProducent::class => [BaseItemProducentObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
