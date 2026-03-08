<?php

namespace App\Filament\Resources\RelicResource\Pages;

use App\Filament\Resources\RelicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelic extends EditRecord
{
    protected static string $resource = RelicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
