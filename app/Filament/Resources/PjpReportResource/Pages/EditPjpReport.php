<?php

namespace App\Filament\Resources\PjpReportResource\Pages;

use App\Filament\Resources\PjpReportResource;
use App\Models\PjpKegiatanReport;
use App\Models\PjpSchedule;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class EditPjpReport extends EditRecord
{
    protected static string $resource = PjpReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Laporan PJP';
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data['status'] = 'SELESAI';

        $record->update($data);

        return $record;
    }
}
