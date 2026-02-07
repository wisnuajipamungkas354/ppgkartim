<?php

namespace App\Filament\Resources\UangKasResource\Pages;

use App\Filament\Exports\UangKasExporter;
use App\Filament\Resources\UangKasResource;
use App\Filament\Resources\UangKasResource\Widgets\KasOverview;
use Filament\Actions;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListUangKas extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = UangKasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->icon('heroicon-o-plus'),
            Actions\ExportAction::make()
                ->label('Excel')
                ->exporter(UangKasExporter::class)
                ->formats([
                        ExportFormat::Xlsx,
                ])
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            KasOverview::class
        ];
    }
}
