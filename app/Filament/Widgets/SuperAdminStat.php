<?php

namespace App\Filament\Widgets;

use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\Kelompok;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuperAdminStat extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Daerah', Daerah::count())
                ->chart([1,1,1])
                ->chartColor('gray'),
            Stat::make('Total Desa', Desa::count())
                ->chart([1,1,1])
                ->chartColor('info'),
            Stat::make('Total Kelompok', Kelompok::count())
                ->chart([1,1,1])
                ->chartColor('success'),
            Stat::make('Total User', User::count())
                ->chart([1,1,1])
                ->chartColor('danger'),
            Stat::make('Total Generus', Generus::count())
                ->chart([1,1,1])
                ->chartColor('warning'),
        ];
    }
}
