<?php

namespace App\Filament\Resources\FgdSessionResource\Pages;

use App\Filament\Resources\FgdSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFgdSession extends EditRecord
{
    protected static string $resource = FgdSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
