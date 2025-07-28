<?php

namespace App\Filament\Resources\DaerahResource\Pages;

use App\Filament\Resources\DaerahResource;
use App\Models\Daerah;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageDaerahs extends ManageRecords
{
    protected static string $resource = DaerahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah')
                ->modalHeading('Tambah Daerah')
                ->modalCancelActionLabel('Batal')
                ->createAnother(false)
                ->modalSubmitActionLabel('Simpan')
                ->successNotificationTitle('Berhasil ditambahkan'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Daerah';
    }
}
