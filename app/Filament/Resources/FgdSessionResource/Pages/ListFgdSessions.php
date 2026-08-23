<?php

namespace App\Filament\Resources\FgdSessionResource\Pages;

use App\Filament\Resources\FgdSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFgdSessions extends ListRecords
{
    protected static string $resource = FgdSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
