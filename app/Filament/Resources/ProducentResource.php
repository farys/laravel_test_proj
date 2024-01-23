<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Inputs\TextInputWithSlugAttachedBuilder;
use App\Filament\Resources\ProducentResource\Pages;
use App\Filament\Resources\ProducentResource\ProducentImageFileUploadBuilder;
use App\Models\BaseItemProducent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class ProducentResource extends Resource
{
    protected static ?string $model = BaseItemProducent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): string
    {
        return __('Producent');
    }

    public static function getPluralLabel(): string
    {
        return __('Producents');
    }

    public static function getRecordTitle(?Model $record): string|null|Htmlable
    {
        return $record->name;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInputWithSlugAttachedBuilder::make('name', 'link')
                    ->required(),
                TextInput::make('title'),
                TextInput::make('link')
                    ->required()
                    ->unique(BaseItemProducent::class, 'link', ignoreRecord: true),
                ProducentImageFileUploadBuilder::make('image_file_name'),
                TextInput::make('min_delivery_days')
                    ->rule('gt:0')
                    ->required(),
                TextInput::make('max_delivery_days')
                    ->gt('min_delivery_days')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                ImageColumn::make('image_file_name'),
                TextColumn::make('base_items_count')->counts('baseItems'),
                TextColumn::make('min_delivery_days'),
                TextColumn::make('max_delivery_days'),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducents::route('/'),
            'create' => Pages\CreateProducent::route('/create'),
            'edit' => Pages\EditProducent::route('/{record}/edit'),
        ];
    }
}
