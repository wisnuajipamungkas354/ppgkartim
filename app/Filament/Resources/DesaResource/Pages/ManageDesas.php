<?php

namespace App\Filament\Resources\DesaResource\Pages;

use App\Filament\Resources\DesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageDesas extends ManageRecords
{
    protected static string $resource = DesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->modalHeading('Tambah Desa')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false)
                ->modalSubmitActionLabel('Simpan')
                ->successNotificationTitle('Berhasil ditambahkan')
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Desa';
    }
}
