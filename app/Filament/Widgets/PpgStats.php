<?php

namespace App\Filament\Widgets;

use App\Models\Desa;
use App\Models\Generus;
use App\Models\Kelompok;
use App\Traits\HandlesPermissionWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PpgStats extends BaseWidget
{
    use HandlesPermissionWidget;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Desa', Desa::count())
                ->chart([1,1,1])
                ->chartColor('info'),
            Stat::make('Total Kelompok', Kelompok::count())
                ->chart([1,1,1])
                ->chartColor('success'),            
            Stat::make('Total Generus', Generus::count())
                ->chart([1,1,1])
                ->chartColor('danger'),
        ];
    }
}
