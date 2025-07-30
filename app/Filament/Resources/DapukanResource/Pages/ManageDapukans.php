<?php

namespace App\Filament\Resources\DapukanResource\Pages;

use App\Filament\Resources\DapukanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDapukans extends ManageRecords
{
    protected static string $resource = DapukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->modalHeading('Tambah Dapukan')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false)
                ->modalSubmitActionLabel('Simpan')
                ->successNotificationTitle('Berhasil ditambahkan')
        ];
    }
}
