<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelicResource\Pages;
use App\Models\Relic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RelicResource extends Resource
{
    protected static ?string $model = Relic::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Gallery Museum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Peninggalan Sejarah')
                    ->description('Masukkan informasi benda sejarah, prasasti, atau pusaka.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Benda')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('origin')
                            ->label('Asal (Kerajaan/Daerah)')
                            ->placeholder('Contoh: Kerajaan Majapahit'),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('material')
                                ->label('Bahan Material')
                                ->placeholder('Contoh: Perunggu, Emas, Batu'),

                            Forms\Components\TextInput::make('estimated_age')
                                ->label('Perkiraan Usia/Abad')
                                ->placeholder('Contoh: Abad ke-14'),
                        ]),

                        Forms\Components\FileUpload::make('image_path')
                            ->label('Foto Benda')
                            ->image()
                            ->disk('public')
                            ->directory('relics')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->columnSpanFull()
                            ->required(),

                        Forms\Components\RichEditor::make('description_id')
                            ->label('Deskripsi (ID)')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description_en')
                            ->label('Description (EN)')
                            ->columnSpanFull(),
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
                    ->label('Nama Benda')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('origin')
                    ->label('Asal')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
            'index' => Pages\ListRelics::route('/'),
            'create' => Pages\CreateRelic::route('/create'),
            'edit' => Pages\EditRelic::route('/{record}/edit'),
        ];
    }
}
