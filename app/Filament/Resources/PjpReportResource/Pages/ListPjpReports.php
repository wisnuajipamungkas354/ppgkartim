<?php

namespace App\Filament\Resources\PjpReportResource\Pages;

use App\Filament\Resources\PjpReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListPjpReports extends ListRecords
{
    protected static string $resource = PjpReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()
            //     ->label('Buat Laporan')
            //     ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Laporan PJP';
    }
}
