<?php

namespace App\Filament\Widgets;

use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\Kelompok;
use App\Models\User;
use App\Traits\HandlesPermissionWidget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Features\SupportEvents\HandlesEvents;

class SuperAdminStat extends BaseWidget
{
    use HandlesPermissionWidget;

    protected function getStats(): array
    {
        return [
            Stat::make('Daerah', Daerah::count())
                ->chart([1,1,1])
                ->chartColor('gray'),
            Stat::make('Desa', Desa::count())
                ->chart([1,1,1])
                ->chartColor('info'),
            Stat::make('Kelompok', Kelompok::count())
                ->chart([1,1,1])
                ->chartColor('success'),
            Stat::make('User', User::count())
                ->chart([1,1,1])
                ->chartColor('danger'),
            Stat::make('Generus', Generus::count())
                ->chart([1,1,1])
                ->chartColor('warning'),
        ];
    }
}
