<?php

namespace App\Filament\Resources\LaporanPjpResource\Pages;

use App\Filament\Resources\LaporanPjpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanPjp extends EditRecord
{
    protected static string $resource = LaporanPjpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
