<?php

namespace App\Filament\Widgets;

use App\Helpers\AccessHelper;
use App\Models\Generus;
use App\Traits\HandlesPermissionWidget;
use Filament\Widgets\ChartWidget;

class KategoriGenerusPie extends ChartWidget
{
    use HandlesPermissionWidget;

    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '20rem';

    protected static ?string $heading = 'Kategori';

    protected function getData(): array
    {
        $generus = Generus::owned()->get();
        $data = [];
        $labels = [];
        $bgColors = [];

        if(AccessHelper::isMudamudi()) {
            $data[0] = $generus->where('kategori', 'PRA_REMAJA')->count();
            $data[1] = $generus->where('kategori', 'REMAJA')->count();
            $data[2] = $generus->where('kategori', 'PRA_NIKAH')->count();

            $labels = ['PRA REMAJA', 'REMAJA', 'PRA NIKAH'];
            $bgColors = [
                        'rgb(255, 159, 64)',   // Oranye
                        'rgb(201, 203, 207)',  // Abu
                        'rgb(100, 181, 246)',  // Biru Langit
            ];
        } else {
            $data[0] = $generus->where('kategori', 'PAUD')->count();
            $data[1] = $generus->where('kategori', 'CABERAWIT')->count();
            $data[2] = $generus->where('kategori', 'PRA_REMAJA')->count();
            $data[3] = $generus->where('kategori', 'REMAJA')->count();
            $data[4] = $generus->where('kategori', 'PRA_NIKAH')->count();

            $labels = ['PAUD', 'CABERAWIT', 'PRA REMAJA', 'REMAJA', 'PRA NIKAH'];
            $bgColors = [
                        'rgb(75, 192, 192)',   // Aqua
                        'rgb(153, 102, 255)',  // Ungu
                        'rgb(255, 159, 64)',   // Oranye
                        'rgb(201, 203, 207)',  // Abu
                        'rgb(100, 181, 246)',  // Biru Langit
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kategori',
                    'data' => $data,
                    'backgroundColor' => $bgColors
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
