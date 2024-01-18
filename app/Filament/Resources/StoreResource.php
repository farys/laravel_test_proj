<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Filament\Traits\ValidationRulesAsArray;
use App\Models\Store;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Navigation\NavigationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\CategoryResource\Pages as CategoryPages;
use App\Filament\Resources\ItemResource\Pages as ItemPages;

class StoreResource extends Resource
{
    use ValidationRulesAsArray;

    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): string
    {
        return __('Store');
    }

    public static function getPluralLabel(): string
    {
        return __('Stores');
    }

    public static function getRecordTitle(?Model $record): string|null|Htmlable
    {
        return $record->domain;
    }

    public static function form(Form $form): Form
    {
        self::validationRules($form, Store::rules());

        $form
            ->schema([
                RichEditor::make('global_announcement'),

                Grid::make(4)->schema([

                    TextInput::make('domain')
                        ->prefix('https://')
                        ->suffixIcon('heroicon-m-globe-alt')
                        ->required(),
                    TextInput::make('site_address')
                        ->required(),
                    TextInput::make('site_title')
                        ->required(),
                    FileUpload::make('watermark_filename')
                        ->directory("uploads/store_watermark")
                        ->image()
                        ->imageEditor()
                        ->avatar()
                        ->columns(1)
                        ->getUploadedFileNameForStorageUsing(
                            fn(Get $get, TemporaryUploadedFile $file): string => (string) $get('domain') . '.' . $file->guessExtension(),
                        ),

                    Fieldset::make('Company teleinfo')->schema([

                        TextInput::make('telephone'),
                        TextInput::make('company_name'),
                        TextInput::make('code_postal'),
                        TextInput::make('city'),
                        TextInput::make('street'),

                    ]),

                    Fieldset::make('Payment & delivery section')->schema([

                        TextInput::make('account_number')
                            ->required(),
                        TextInput::make('account_receiver')
                            ->required(),
                        TextInput::make('account_bank_name')
                            ->required(),
                        Checkbox::make('payment_on_place')
                            ->default(false),
                        Checkbox::make('take_on_place')
                            ->default(false),

                    ]),

                ])->columnSpan(4),
                Grid::make(4)->schema([
                    Fieldset::make('Mail account credentials')->schema([

                        TextInput::make('mail_smtp_address')
                            ->columnSpan(1),
                        TextInput::make('mail_address')
                            ->columnSpan(1),
                        TextInput::make('mail_author_title')
                            ->helperText('Author of email'),
                        TextInput::make('mail_login')
                            ->columnSpan(1),
                        TextInput::make('mail_password')
                            ->columnSpan(1),
                        Textarea::make('mail_footer')
                            ->rows(4),

                    ]),

                    Fieldset::make('Mail blocks')->schema([

                        RichEditor::make('mail_block'),
                        RichEditor::make('mail_block2'),

                    ]),
                ])->columnSpan(8),

                //TextInput::make('login'),
                //TextInput::make('password'),

            ])->columns(12);

        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("domain"),
                TextColumn::make("company_name"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),


            'categories.index' => CategoryPages\ListCategories::route('/{parent}/categories'),
            'categories.create' => CategoryPages\CreateCategory::route('/{parent}/categories/create'),
            'categories.edit' => CategoryPages\EditCategory::route('/{parent}/categories/{record}/edit'),
            'categories.view' => CategoryPages\ViewCategory::route('/{parent}/categories/{record}'),

            'items.index' => ItemPages\ListItems::route('/{parent}/items'),
            'items.create' => ItemPages\CreateItem::route('/{parent}/items/create'),
            'items.edit' => ItemPages\EditItem::route('/{parent}/items/{record}/edit'),

        ];
    }
}
