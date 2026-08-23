<?php

namespace App\Filament\Resources\FgdNoteResource\Pages;

use App\Filament\Resources\FgdNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFgdNotes extends ListRecords
{
    protected static string $resource = FgdNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
