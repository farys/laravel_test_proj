<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Actions\ViewAction;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers\ChildrenRelationManager;
use App\Models\Category;
use App\Models\Store;
use App\Providers\Filament\AdminPanelProvider;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

class CategoryResource extends Resource
{
    public static string $parentResource = StoreResource::class;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): string
    {
        return __('Category');
    }

    public static function getPluralLabel(): string
    {
        return __('Categories');
    }

    public static function getRecordTitle(?Model $record): string|null|Htmlable
    {
        return $record->name . ' (' . $record->store->domain . ')';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('store_id')
                    ->default(function (Request $request) {
                        return $request->route('parent');
                    }),
                Fieldset::make('Store')
                    ->relationship('store')
                    ->schema([
                        TextInput::make('domain')
                            ->disabled()
                            ->default(function (Request $request) {
                                return Store::find($request->route('parent'))->domain;
                            }),
                    ]),
                TextInput::make('title'),
                TextInput::make('name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                        if (empty($get('link')))
                            $set('link', \Str::slug($state));
                    }),
                TextInput::make('link'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('children_count')->counts('children'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->url(
                        fn(Category $record): string => Pages\EditCategory::getParentResource()::getUrl('categories.edit', [
                            'parent' => $record->store_id,
                            'record' => $record->id,
                        ])
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->where('parent_id', '=', null));
    }

    public static function getRelations(): array
    {
        return [
            ChildrenRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            // 'index' => Pages\ListCategories::route('/'),
            // 'create' => Pages\CreateCategory::route('/create'),
            // 'view' => Pages\ViewCategory::route('/{record}'),
            // 'edit' => Pages\EditCategory::route('/{record}/edit'),
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
                ->isActiveWhen(function(Request $request) use ($id){
                    return $request->routeIs(static::getRouteBaseName() . '.*') && $request->route('parent') == $id;
                })
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->sort(static::getNavigationSort())
                ->url(StoreResource::getUrl('categories.index', ['parent' => $id]));
        }

        return $items;
    }

    public static function getRouteBaseName(?string $panel = null): string
    {
        return StoreResource::getRouteBaseName($panel) . '.categories';
    }
}
