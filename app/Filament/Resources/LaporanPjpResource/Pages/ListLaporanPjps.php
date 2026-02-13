<?php

namespace App\Filament\Resources\LaporanPjpResource\Pages;

use App\Filament\Resources\LaporanPjpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListLaporanPjps extends ListRecords
{
    protected static string $resource = LaporanPjpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Laporan')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Laporan PJP';
    }
}
