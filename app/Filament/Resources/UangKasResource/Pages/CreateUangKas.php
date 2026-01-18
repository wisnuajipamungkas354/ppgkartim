<?php

namespace App\Filament\Resources\UangKasResource\Pages;

use App\Filament\Resources\UangKasResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUangKas extends CreateRecord
{
    protected static string $resource = UangKasResource::class;

    protected static ?string $title = 'Tambah Data Kas';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Berhasil ditambahkan!')
            ->body('Data kas berhasil ditambahkan.');
    }
}
