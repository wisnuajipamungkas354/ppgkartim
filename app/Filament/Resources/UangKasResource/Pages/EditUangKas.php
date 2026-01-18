<?php

namespace App\Filament\Resources\UangKasResource\Pages;

use App\Filament\Resources\UangKasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUangKas extends EditRecord
{
    protected static string $resource = UangKasResource::class;

    protected static ?string $title = 'Edit Data Kas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }
}
