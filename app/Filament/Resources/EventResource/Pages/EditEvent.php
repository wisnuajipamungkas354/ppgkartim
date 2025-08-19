<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
            ->label('Hapus')
                ->modalHeading('Hapus Data Event')
                ->modalDescription('Apakah kamu yakin ingin menghapus event ini ?')
                ->modalSubmitActionLabel('Ya')
                ->modalCancelActionLabel('Batal')
                ->action(function(array $data, Event $record) {
                    if($record->poster_image != null) Storage::delete($record->poster_image);
                    $record->delete();
                })
                ->successNotification(fn(Notification $notification) => $notification->title('Dihapus')),
        ];
    }
}
