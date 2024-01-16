<?php

namespace App\Providers;

use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Blade::anonymousComponentPath(__DIR__.'/../../resources/views/admin/components', 'admin');
        if (config("env") == "development") {
            Lang::handleMissingKeysUsing(function (string $key, array $replacements, string $locale) {
                throw new \Exception("Missing translation key '$key' detected.");

                //return $key;
            });
        }

        Column::configureUsing(function (Column $column): void {
            $column->translateLabel();
        });
        Filter::configureUsing(function (Filter $filter): void {
            $filter->translateLabel();
        });
        Field::configureUsing(function (Field $field): void {
            $field->translateLabel()->columnSpanFull();
        });
        Entry::configureUsing(function (Entry $entry): void {
            $entry->translateLabel();
        });

    }
}
