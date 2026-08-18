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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $sessions = $data['sessions'] ?? [];

        if (($data['event_type'] ?? 'single') === 'single') {
            $data['date'] = $sessions[0]['date'] ?? $data['date'] ?? null;
            $data['start_time'] = $sessions[0]['sesi'][0]['start_time'] ?? null;
            $data['end_time'] = $sessions[0]['sesi'][0]['end_time'] ?? null;
            unset($data['sessions']);
        } elseif (($data['event_type'] ?? 'single') === 'multi_session') {
            $data['date'] = $sessions[0]['date'] ?? $data['date'] ?? null;
            $data['sessions'] = $sessions[0]['sesi'] ?? [];
        } elseif (($data['event_type'] ?? 'single') === 'multi_day') {
            $data['days'] = $sessions;
            unset($data['sessions']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['event_type'] ?? 'single') === 'single') {
            $data['sessions'] = [
                [
                    'date' => $data['date'],
                    'sesi' => [
                        [
                            'label' => 'Sesi Tunggal',
                            'start_time' => $data['start_time'] ?? null,
                            'end_time' => $data['end_time'] ?? null,
                        ]
                    ]
                ]
            ];
        } elseif (($data['event_type'] ?? 'single') === 'multi_session') {
            $data['sessions'] = [
                [
                    'date' => $data['date'],
                    'sesi' => $data['sessions'] ?? []
                ]
            ];
        } elseif (($data['event_type'] ?? 'single') === 'multi_day') {
            $data['sessions'] = $data['days'] ?? [];
            if (!empty($data['sessions'][0]['date'])) {
                $data['date'] = $data['sessions'][0]['date']; 
            }
        }

        unset($data['days'], $data['start_time'], $data['end_time']);

        return $data;
    }

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
