<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonumentResource\Pages;
use App\Models\Monument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MonumentResource extends Resource
{
    protected static ?string $model = Monument::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Gallery Museum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Monumen')
                    ->description('Lengkapi detail data monumen atau situs bersejarah.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Monumen')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi/Alamat')
                            ->placeholder('Contoh: Jakarta Pusat, DKI Jakarta')
                            ->required(),

                        Forms\Components\FileUpload::make('image_path')
                            ->label('Foto Monumen')
                            ->image()
                            ->disk('public')
                            ->directory('monuments')
                            ->columnSpanFull()
                            ->required(),

                        Forms\Components\RichEditor::make('description_id')
                            ->label('Deskripsi (ID)')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description_en')
                            ->label('Description (EN)')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('coordinate')
                            ->label('Koordinat Google Maps')
                            ->placeholder('Contoh: -6.175392, 106.827153'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Monumen')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonuments::route('/'),
            'create' => Pages\CreateMonument::route('/create'),
            'edit' => Pages\EditMonument::route('/{record}/edit'),
        ];
    }
}
