<?php

namespace App\Filament\Resources\ImageResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RepresentedBaseItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'representedBaseItems';

    protected static ?string $inverseRelationship = 'image';

    public static function getTitle(Model $ownerRecord, string $pageClass) : string
    {
        return __("Represented base items");
    }

    public function form(Form $form) : Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table) : Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                ]),
            ]);
    }
}
