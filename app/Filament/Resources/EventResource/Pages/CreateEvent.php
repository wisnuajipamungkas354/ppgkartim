<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $data['user_id']    = auth()->user()->id;
            $data['role_id']    = session('active_role_id');
            $data['is_active']  = true;
            $data['kode_event'] = random_int(1000, 9999);

            // Standarisasi format JSON sessions agar selalu seragam:
            // [ { "date": "...", "sesi": [ { "label": "...", "start_time": "...", "end_time": "..." } ] } ]
            
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
                    // Update field date utama berdasarkan hari pertama untuk query gampang
                    $data['date'] = $data['sessions'][0]['date']; 
                }
            }

            // Hapus field dummy yang tidak ada di table events
            unset($data['days'], $data['start_time'], $data['end_time']);

            return static::getModel()::create($data);
        } catch(\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('CreateEvent error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            Notification::make('failed')
                ->title('Gagal menambahkan data event')
                ->body($e->getMessage()) // tampilkan error sementara untuk debug
                ->danger()
                ->send();

            throw new \Exception("Gagal menyimpan record: " . $e->getMessage(), 500);
        }

    }

    public function getTitle(): string|Htmlable
    {
        return 'Buat Event';
    }
}
