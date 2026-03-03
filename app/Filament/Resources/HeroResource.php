<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroResource\Pages;
use App\Models\Hero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class HeroResource extends Resource
{
    protected static ?string $model = Hero::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Informasi Lengkap Pahlawan')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),

                        \Filament\Forms\Components\Grid::make(3)->schema([
                            \Filament\Forms\Components\Select::make('category')
                                ->label('Kategori')
                                ->options([
                                    'National Hero' => 'National Hero',
                                    'Revolutionary' => 'Revolutionary',
                                    'Military Leader' => 'Military Leader',
                                    'Education Pioneer' => 'Education Pioneer',
                                ])
                                ->required()
                                ->native(false),

                            \Filament\Forms\Components\DatePicker::make('birth_date')
                                ->label('Tanggal Lahir')
                                ->native(false)
                                ->displayFormat('d/m/Y'),

                            \Filament\Forms\Components\DatePicker::make('death_date')
                                ->label('Tanggal Wafat')
                                ->native(false)
                                ->displayFormat('d/m/Y'),
                        ]),

                        \Filament\Forms\Components\TextInput::make('hometown')
                            ->label('Asal Daerah')
                            ->required(),

                        \Filament\Forms\Components\Hidden::make('slug')
                            ->required(),

                        \Filament\Forms\Components\FileUpload::make('image_path')
                            ->label('Foto Pahlawan')
                            ->image()
                            ->disk('public')
                            ->directory('img')
                            ->required(),

                        \Filament\Forms\Components\Tabs::make('Content')
                            ->tabs([
                                \Filament\Forms\Components\Tabs\Tab::make('Bahasa Indonesia')->schema([
                                    \Filament\Forms\Components\Textarea::make('bio_id')
                                        ->label('Riwayat & Perjuangan (ID)')
                                        ->rows(10)
                                        ->required(),
                                    \Filament\Forms\Components\TextInput::make('quotes')
                                        ->label('Kutipan/Quotes'),
                                ]),
                                \Filament\Forms\Components\Tabs\Tab::make('English')->schema([
                                    \Filament\Forms\Components\Textarea::make('bio_en')
                                        ->label('History & Struggle (EN)')
                                        ->rows(10),
                                ]),
                            ])
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('image_path')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),

                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pahlawan')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),

                \Filament\Tables\Columns\TextColumn::make('hometown')
                    ->label('Asal')
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('birth_date')
                    ->label('Lahir')
                    ->date('Y')
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('quotes')
                    ->label('Kutipan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'National Hero' => 'National Hero',
                        'Revolutionary' => 'Revolutionary',
                        'Military Leader' => 'Military Leader',
                        'Education Pioneer' => 'Education Pioneer',
                    ]),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroes::route('/'),
            'create' => Pages\CreateHero::route('/create'),
            'edit' => Pages\EditHero::route('/{record}/edit'),
        ];
    }
}
