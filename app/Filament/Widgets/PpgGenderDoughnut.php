<?php

namespace App\Filament\Widgets;

use App\Models\Generus;
use App\Traits\HandlesPermissionWidget;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PpgGenderDoughnut extends ChartWidget
{
    use HandlesPermissionWidget;
    
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Jenis Kelamin';

    protected function getData(): array
    {
        $data = [];
        $data[0] = Generus::query()->whereHas('insan', fn(Builder $query) => $query->where('jk', 'L'))->count();
        $data[1] = Generus::query()->whereHas('insan', fn(Builder $query) => $query->where('jk', 'P'))->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jenis Kelamin',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                    ]
                ],
            ],
            'labels' => ['Laki-laki', 'Perempuan'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
