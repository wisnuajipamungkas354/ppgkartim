<?php

namespace App\Filament\Resources\KelasPpgResource\Pages;

use App\Filament\Resources\KelasPpgResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKelasPpgs extends ManageRecords
{
    protected static string $resource = KelasPpgResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->modalHeading('Tambah Kelas')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false)
                ->modalSubmitActionLabel('Simpan')
                ->successNotificationTitle('Berhasil ditambahkan')
        ];
    }
}
