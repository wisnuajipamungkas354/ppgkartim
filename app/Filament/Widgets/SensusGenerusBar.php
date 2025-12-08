<?php

namespace App\Filament\Widgets;

use App\Helpers\AccessHelper;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\Kelompok;
use App\Traits\HandlesPermissionWidget;
use Filament\Widgets\ChartWidget;

class SensusGenerusBar extends ChartWidget
{
    use HandlesPermissionWidget;

    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '18rem';

    protected static ?string $heading = 'Sensus Generus';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $categories = [];
        $wilayahList = [];
        $colors = [];

        /** 
         * $datasets = [
        *       [
        *           label   => 'PAUD' / 'CABERAWIT' / 'PRA REMAJA' / 'REMAJA' / 'PRA NIKAH',
        *           data    => dataPerdesa/Kelompok
        *           backgroundColor => $colors[$i % count($colors)],
        *       ]
         * ];
        */

        if(AccessHelper::isMudamudi()) {
            $categories = ['PRA_REMAJA', 'REMAJA', 'PRA_NIKAH'];
            $colors = [
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
            ];
        } else {
            $categories = ['PAUD', 'CABERAWIT', 'PRA_REMAJA', 'REMAJA', 'PRA_NIKAH'];
            $colors = [
                'rgb(54, 162, 235)',
                'rgb(255, 99, 132)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
            ];
        }

        $datasets = [];

        if(AccessHelper::isDaerah()) {
            $wilayahList = Desa::owned()->pluck('nm_desa');
            
            foreach ($categories as $i => $category) {
                $label = str_replace('_', ' ', $category);

                $datasets[] = [
                    'label' => $label,
                    'data' => $wilayahList->map(function ($desa) use ($category) {
                            return Generus::with('insan.desa')->whereHas('insan.desa', function($q) use ($desa) {
                                return $q->where('nm_desa', $desa);
                            })
                            ->where('kategori', $category)
                            ->count();
                    }),
                    'backgroundColor' => $colors[$i] // warna random stabil
                ];
            }
        } elseif(AccessHelper::isDesa()) {
            $wilayahList = Kelompok::owned()->pluck('nm_kelompok');
            
            foreach ($categories as $i => $category) {
                $label = str_replace('_', ' ', $category);

                $datasets[] = [
                    'label' => $label,
                    'data' => $wilayahList->map(function ($kelompok) use ($category) {
                            return Generus::with('insan.kelompok')->whereHas('insan.kelompok', function($q) use ($kelompok) {
                                return $q->where('nm_kelompok', $kelompok);
                            })
                            ->where('kategori', $category)
                            ->count();
                    }),
                    'backgroundColor' => $colors[$i]
                ];
            }
        }

        return [
            'labels' => $wilayahList,
            'datasets' => $datasets,
        ];          
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
