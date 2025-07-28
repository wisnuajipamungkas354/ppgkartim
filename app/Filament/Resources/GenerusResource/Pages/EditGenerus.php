<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use App\Models\Generus;
use App\Models\InsanRole;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;

class EditGenerus extends EditRecord
{
    protected static string $resource = GenerusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $insan = InsanRole::with('insan')->find($data['insan_role_id'])?->insan;

        if (!$insan) {
            return $data; // atau bisa throw exception kalau perlu
        }

        return array_merge($data, $insan->only([
            'daerah_id', 'desa_id', 'kelompok_id', 'nama', 'jk',
            'kota_lahir', 'tgl_lahir', 'no_hp', 'pendidikan_terakhir', 'jurusan',
        ]));
    }
}
