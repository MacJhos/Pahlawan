<?php

namespace App\Filament\Resources\RelicResource\Pages;

use App\Filament\Resources\RelicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRelics extends ListRecords
{
    protected static string $resource = RelicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
