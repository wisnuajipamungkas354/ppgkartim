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
            $data['user_id'] = auth()->user()->id;
            $data['role_id'] = session('active_role_id');
            $data['is_active'] = true;
            $data['kode_event'] = random_int(1000, 9999);
            return static::getModel()::create($data);
        } catch(\Throwable $e) {
            Notification::make('failed')
                ->title('Gagal menambahkan data event')
                ->danger()
                ->send();

            throw new \Exception("Gagal menyimpan record", 500);
        }

    }

    public function getTitle(): string|Htmlable
    {
        return 'Buat Event';
    }
}
