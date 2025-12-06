<?php

namespace App\Filament\Widgets;

use App\Helpers\AccessHelper;
use App\Models\Desa;
use App\Models\Generus;
use App\Traits\HandlesPermissionWidget;
use Filament\Widgets\ChartWidget;

class SensusGenerusBar extends ChartWidget
{
    use HandlesPermissionWidget;

    protected static ?int $sort = 4;
    protected static ?string $heading = 'Sensus Generus';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $generus = Generus::owned();
        $labels = [];
        $colors = [];

        if(AccessHelper::isDaerah()) {
            $generus = $generus->with('insan.desa')->get();

        } elseif(AccessHelper::isDesa()) {
            $generus = $generus->with('insan.kelompok')->get();
        }

        if(AccessHelper::isMudamudi()) {
            $labels = ['PRA_REMAJA', 'REMAJA', 'PRA_NIKAH'];
            $colors = [
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
            ];
        } else {
            $labels = ['PAUD', 'CABERAWIT', 'PRA_REMAJA', 'REMAJA', 'PRA_NIKAH'];
            $colors = [
                'rgb(54, 162, 235)',
                'rgb(255, 99, 132)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
            ];
        } 

        $datasets = [];
        
        // foreach ($labels as $i => $label) {
        //     $dataPerKategori = [];

        //     foreach ($kategoriList as $kategori) {
        //         $jumlah = Generus::where('kategori', $kategori)
        //             ->whereHas('insan.desa', function ($q) use ($label) {
        //                 $q->where('id', $label->id);
        //             })->count();
        
        //         $dataPerKategori[] = $jumlah;
        //     }

        //     $datasets[] = [
        //         'label' => $label->nm_desa,
        //         'data' => $dataPerKategori,
        //         'backgroundColor' => $colors[$i % count($colors)],
        //     ];    
        // }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];          
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
