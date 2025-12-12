<?php

namespace App\Filament\Resources\MubalighResource\Pages;

use App\Filament\Resources\MubalighResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateMubaligh extends CreateRecord
{
    protected static string $resource = MubalighResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Tambah Data Mubaligh';
    }

}
