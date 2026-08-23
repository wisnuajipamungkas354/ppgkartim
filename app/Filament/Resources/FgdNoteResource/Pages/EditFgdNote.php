<?php

namespace App\Filament\Resources\FgdNoteResource\Pages;

use App\Filament\Resources\FgdNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFgdNote extends EditRecord
{
    protected static string $resource = FgdNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
