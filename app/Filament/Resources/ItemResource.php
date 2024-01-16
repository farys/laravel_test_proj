<?php

namespace App\Filament\Resources;

use App\Enums\BaseItemStatus;
use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Traits\ValidationRulesAsArray;
use App\Models\Item;
use App\Models\Store;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ItemResource extends Resource
{
    use ValidationRulesAsArray;

    public static string $parentResource = StoreResource::class;

    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): string
    {
        return __('Item');
    }

    public static function getPluralLabel(): string
    {
        return __('Items');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('store_id')
                    ->relationship(name: 'store', titleAttribute: 'domain')
                    ->default(function () {
                        return request()->route()->parameters['parent'];
                    })->required(),
                Select::make('base_item_id')->relationship(name: 'baseItem', titleAttribute: 'name')->searchable(['name'])->preload()->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        /*Forms\Components\TextInput::make('email')
                        ->required()
                        ->email(),*/
                    ]),
                //TextInput::make('base_item_id'),
                TextInput::make('name'),
                TextInput::make('title')
                    ->afterStateUpdated(function (string $operation, Get $get, Set $set, ?string $state) {
                        if ($operation == 'create' && !$get('link') && filled($state)) {
                            $set('link', Str::slug($state));
                        }
                    })
                    ->live(onBlur: true)
                    ->required(),
                TextInput::make('link'),
                TextInput::make('price'),
                TextInput::make('old_price'),
                TextInput::make('transport'),
                TextInput::make('promotion'),
                TextInput::make('courier_id'),
                Fieldset::make(null)
                    ->schema([
                        DateTimePicker::make('created_at')->disabled(),
                        DateTimePicker::make('updated_at')->disabled(),
                        DateTimePicker::make('price_changed_at')->disabled(),
                    ]),
                TextInput::make('seo_text1'),
                TextInput::make('seo_text2'),
                TextInput::make('meta_description'),
                TextInput::make('meta_keywords'),
                Fieldset::make(null)
                    ->schema([
                        Toggle::make('google_merchant'),
                        Toggle::make('export_ceneo'),
                        Toggle::make('export_nokaut'),
                        Toggle::make('export_kreocen'),
                        Toggle::make('export_okazjeinfo'),
                        Toggle::make('export_cenaro'),
                    ]),
                TextInput::make('short_description'),
                TextInput::make('own_description'),
                TextInput::make('load_description'),
                TextInput::make('extra_courier_price'),
                TextInput::make('extra_on_delivery_courier_price'),

                Toggle::make('synchro_price'),
                Toggle::make('synchro_trim_courier'),
                Toggle::make('synchro_change_price'),
                //FileUpload::make('image'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(
                        fn(Pages\ListItems $livewire, Model $record): string => static::$parentResource::getUrl('items.edit', [
                            'record' => $record,
                            'parent' => $livewire->parent,
                        ])
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //RelationManagers\BaseItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }

    public static function getNavigationItems(): array
    {
        $items = [];
        $stores = Store::pluck('domain', 'id');

        foreach ($stores as $id => $store_domain) {
            $items[] = NavigationItem::make(static::getNavigationLabel())
                ->group($store_domain)
                ->parentItem(static::getNavigationParentItem())
                ->icon(static::getNavigationIcon())
                ->activeIcon(static::getActiveNavigationIcon())
                ->isActiveWhen(fn() => request()->routeIs(static::getRouteBaseName() . '.*'))
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->sort(static::getNavigationSort())
                ->url(StoreResource::getUrl('items.index', ['parent' => $id]));
        }

        return $items;
    }
}
