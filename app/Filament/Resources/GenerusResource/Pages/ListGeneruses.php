<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use App\Filament\Resources\GenerusResource\Widgets\CountGenerusTable;
use App\Helpers\AccessHelper;
use App\Models\Generus;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListGeneruses extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = GenerusResource::class;

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
        return 'Data Generus';
    }

    public function getHeaderWidgets() :array {
        return [
            CountGenerusTable::class,
        ];
    }


    public function getTabs(): array
    {
        // Jika role aktif adalah Lingkup PPG atau Super admin maka
        if(AccessHelper::isPpg() || AccessHelper::isSuperAdmin()) 
        {
            return [
                'Semua' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query)
                        ->badge(Generus::where('is_verified', true)->count()),
                'Paud' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('kategori', '=', 'PAUD'))
                        ->badge(Generus::query()->where('is_verified', true)->where('kategori', '=', 'PAUD')->count()),
                'Caberawit' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('kategori', '=', 'CABERAWIT'))
                        ->badge(Generus::query()->where('is_verified', true)->where('kategori', '=', 'CABERAWIT')->count()),
                'Pra Remaja' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('kategori', '=', 'PRA_REMAJA'))
                        ->badge(Generus::query()->where('is_verified', true)->where('kategori', '=', 'PRA_REMAJA')->count()),
                'Remaja' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('kategori', '=', 'REMAJA'))
                        ->badge(Generus::query()->where('is_verified', true)->where('kategori', '=', 'REMAJA')->count()),
                'Pra Nikah' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('kategori', '=', 'PRA_NIKAH'))
                        ->badge(Generus::query()->where('is_verified', true)->where('kategori', '=', 'PRA_NIKAH')->count()),
            ];
        //     Jika bukan berarti role tersebut adalah kelompok muda/i
        } else {
            return [
                'Semua' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('jenis_data', 'MM'))
                        ->badge(Generus::query()->where('is_verified', true)->where('jenis_data', 'MM')->count()),
                'Pra Remaja' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('kategori', '=', 'PRA_REMAJA'))
                        ->badge(Generus::query()->where('is_verified', true)->where('kategori', '=', 'PRA_REMAJA')->count()),
                'Remaja' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('kategori', '=', 'REMAJA'))
                        ->badge(Generus::query()->where('is_verified', true)->where('kategori', '=', 'REMAJA')->count()),
                'Mahasiswa' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->with('status')->where('kategori', '=', 'PRA_NIKAH')->whereHas('status', fn(Builder $query) => $query->whereIn('slug', ['d3','s1-d4','s2','s3','kuliahkerja'])))
                        ->badge(Generus::with('status')->where('kategori', '=', 'PRA_NIKAH')->whereHas('status', fn(Builder $query) => $query->whereIn('slug', ['d3','s1-d4','s2','s3','kuliahkerja']))->count()),
                'Pra Nikah' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('kategori', '=', 'PRA_NIKAH'))
                        ->badge(Generus::query()->where('is_verified', true)->where('kategori', '=', 'PRA_NIKAH')->count()),
            ];
        }
    }
}
