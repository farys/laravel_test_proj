<?php

namespace App\Filament\Resources;

use App\Enums\StandardActiveStatus;
use App\Filament\Resources\ImageResource\Inputs\AttachmentImageFileUpload;
use App\Filament\Resources\ImageResource\Pages;
use App\Filament\Resources\ImageResource\RelationManagers;
use App\Filament\Resources\ImageResource\RelationManagers\RepresentedBaseItemsRelationManager;
use App\Models\Image;
use Filament\Forms;
use Filament\Forms\Components\BelongsToManyCheckboxList;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('status')
                    ->options(StandardActiveStatus::class)
                    ->default(StandardActiveStatus::ACTIVE)
                    ->disablePlaceholderSelection()
                    ->required(),
                AttachmentImageFileUpload::make('attachment_file_name')
                    ->required(),
                Select::make('baseItems')
                    ->relationship('baseItems', 'name')
                    ->searchable(['name'])
                    ->multiple()
                    ->preload()
                    ->hiddenOn('create')
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                ImageColumn::make('attachment_file_name'),
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

    public static function getRelations() : array
    {
        return [
            RepresentedBaseItemsRelationManager::class
        ];
    }

    public static function getPages() : array
    {
        return [
            'index' => Pages\ListImages::route('/'),
            'create' => Pages\CreateImage::route('/create'),
            'edit' => Pages\EditImage::route('/{record}/edit'),
        ];
    }
}
