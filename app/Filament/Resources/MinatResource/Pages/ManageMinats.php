<?php

namespace App\Filament\Resources\MinatResource\Pages;

use App\Filament\Resources\MinatResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMinats extends ManageRecords
{
    protected static string $resource = MinatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->modalHeading('Tambah Minat')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false)
                ->modalSubmitActionLabel('Simpan')
                ->successNotificationTitle('Berhasil ditambahkan')
        ];
    }
}
