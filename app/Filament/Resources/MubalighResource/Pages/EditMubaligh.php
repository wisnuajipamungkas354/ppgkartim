<?php

namespace App\Filament\Resources\MubalighResource\Pages;

use App\Filament\Resources\MubalighResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMubaligh extends EditRecord
{
    protected static string $resource = MubalighResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
