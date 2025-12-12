<?php

namespace App\Filament\Resources\MubalighResource\Pages;

use App\Filament\Resources\MubalighResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListMubalighs extends ListRecords
{
    protected static string $resource = MubalighResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->icon('heroicon-o-plus')
                ->successNotificationMessage('Data berhasil ditambahkan'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Data Mubaligh';
    }
}
