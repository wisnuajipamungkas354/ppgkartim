<?php

namespace App\Filament\Resources\RoleAccessResource\Pages;

use App\Filament\Resources\RoleAccessResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoleAccesses extends ListRecords
{
    protected static string $resource = RoleAccessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
