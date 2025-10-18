<?php

namespace App\Filament\Resources\GenerusResource\Widgets;

use App\Filament\Resources\GenerusResource\Pages\ListGeneruses;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class CountGenerusTable extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListGeneruses::class;
    }

    protected function getStats(): array
    {
        $total = $this->getPageTableQuery()->count();
        $l = $this->getPageTableQuery()->whereHas('insan', fn(Builder $query) => $query->where('jk', 'L'))->count();
        $p = $this->getPageTableQuery()->whereHas('insan', fn(Builder $query) => $query->where('jk', 'P'))->count();

        return [
            Stat::make('Total', $total)
                ->icon('heroicon-s-users')
                ->chart([1,1])
                ->chartColor('gray'),
            Stat::make('Laki-laki', $l)
                ->icon('heroicon-s-user')
                ->chart([1,1])
                ->chartColor('info'),
            Stat::make('Perempuan', $p)
                ->icon('heroicon-s-user')
                ->chart([1,1])
                ->chartColor('danger'),
        ];
    }
}
