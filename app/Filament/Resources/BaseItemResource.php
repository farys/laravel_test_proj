<?php

namespace App\Filament\Resources;

use App\Enums\BaseItemOrderingStatus;
use App\Enums\BaseItemStatus;
use App\Filament\Resources\BaseItemResource\Pages;
use App\Filament\Resources\BaseItemResource\RelationManagers\ItemsRelationManager;
use App\Models\BaseItem;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseItemResource extends Resource
{
    protected static ?string $model = BaseItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): string
    {
        return __('Base Item');
    }

    public static function getPluralLabel(): string
    {
        return __('Base Items');
    }

    public static function getRecordTitle(?Model $record): string|null|Htmlable
    {
        return $record->name;
    }
    
    public static function form(Form $form): Form
    {

        return $form
            ->schema([

                Grid::make(4)->schema([

                    TextInput::make('name')
                        ->required(),

                    Select::make('status')
                        ->options(BaseItemStatus::class)
                        ->default(BaseItemStatus::ACTIVE)
                        ->disablePlaceholderSelection()
                        ->required(),

                    Select::make('producent_id')
                        ->relationship('producent', 'name')
                        ->searchable(['name'])
                        ->preload()
                        ->required()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required(),
                        ]),

                    Fieldset::make(__('Synchronization'))->schema([

                        TextInput::make('symbol')
                            ->numeric(),
                        TextInput::make('storehouse')
                            ->numeric()
                            ->default(0),
                        Toggle::make('synchro_storehouse'),
                        Select::make('ordering_status')
                            ->options(BaseItemOrderingStatus::class)
                            ->default(BaseItemOrderingStatus::NONE->value)
                            ->disablePlaceholderSelection()
                            ->rules(BaseItem::validationRules()['ordering_status'])
                            ->required(),

                    ])->columnSpanFull(),

                    TextInput::make('priority')
                        ->numeric(),
                    TextInput::make('symbol')
                        ->numeric(),
                    TextInput::make('producent_symbol')
                        ->numeric(),
                    TextInput::make('ean_code')
                        ->numeric(),

                ])->columnSpan(3),

                Grid::make()->schema([

                    Fieldset::make(__('Descriptions'))->schema([

                        Textarea::make('short_description')->columnSpanFull()->helperText(__("tips.base_item.short_description.help")),
                        RichEditor::make('own_description')->columnSpanFull(),
                        Select::make('description_id')
                            ->relationship('description', 'name')
                            ->columnSpanFull(),

                    ]),

                    Fieldset::make(__('Logistics'))->schema([

                        TextInput::make('weight')->numeric()->suffix('kg')->required(),
                        TextInput::make('width')->numeric()->suffix('cm')->required()->columnSpan(3),
                        TextInput::make('depth')->numeric()->suffix('cm')->required()->columnSpan(3),
                        TextInput::make('height')->numeric()->suffix('cm')->required()->columnSpan(3),

                    ])->columns(9),

                    Fieldset::make(__('Warranties'))->schema([

                        Repeater::make('warranties')
                            ->relationship()
                            ->defaultItems(0)
                            ->disableLabel()
                            ->live(onBlur: true)
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                            ->schema([

                                TextInput::make("name")->columnSpan(4),
                                Toggle::make("internal")->columnSpan(2),
                                TextInput::make("period")->numeric()->columnSpan(1),

                            ])->columns(8),
                    ])->columns(8),
                ])->columnSpan(9),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('status')
            ])
            ->filters([
                Filter::make('hidden')->query(fn(Builder $query): Builder => $query->hidden())
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            ItemsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBaseItems::route('/'),
            'create' => Pages\CreateBaseItem::route('/create'),
            'edit' => Pages\EditBaseItem::route('/{record}/edit'),
        ];
    }
}
