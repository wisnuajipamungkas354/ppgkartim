<?php

namespace App\Filament\Resources\LaporanPjpResource\Pages;

use App\Filament\Resources\LaporanPjpResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Js;

class CreateLaporanPjp extends CreateRecord
{
    protected static string $resource = LaporanPjpResource::class;

    protected static bool $canCreateAnother = false;

    public function getTitle(): string|Htmlable
    {
        return 'Buat Laporan';
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Buat Laporan')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batal')
            ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? static::getResource()::getUrl()) . ')')
            ->color('gray');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth('web')->user();
        
        $data['reportable_type'] = $user->userable_type;
        $data['reportable_id'] = $user->userable_id;

        return $data;
    }
}
