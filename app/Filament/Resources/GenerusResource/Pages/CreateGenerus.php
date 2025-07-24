<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateGenerus extends CreateRecord
{
    protected static string $resource = GenerusResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Tambah Data Generus';
    }
}
