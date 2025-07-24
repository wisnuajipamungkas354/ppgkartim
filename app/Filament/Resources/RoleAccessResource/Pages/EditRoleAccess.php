<?php

namespace App\Filament\Resources\RoleAccessResource\Pages;

use App\Filament\Resources\RoleAccessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoleAccess extends EditRecord
{
    protected static string $resource = RoleAccessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
