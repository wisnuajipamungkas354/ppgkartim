<?php

namespace App\Filament\Resources\StatusResource\Pages;

use App\Filament\Resources\StatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageStatuses extends ManageRecords
{
    protected static string $resource = StatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->modalHeading('Tambah Status')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false)
                ->modalSubmitActionLabel('Simpan')
                ->successNotificationTitle('Berhasil ditambahkan')
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'List Status';
    }
}
