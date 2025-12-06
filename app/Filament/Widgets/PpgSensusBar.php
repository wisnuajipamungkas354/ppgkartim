<?php

namespace App\Filament\Widgets\Ppg;

use App\Models\Desa;
use App\Models\Generus;
use App\Traits\HandlesPermissionWidget;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class PpgSensusBar extends ChartWidget
{
    use HandlesPermissionWidget;

    protected static ?int $sort = 4;
    protected static ?string $heading = 'Sensus Generus Per Desa';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $generus = Generus::query()->with('insan.desa')->get();
        $labels = Desa::query()->get(['id', 'nm_desa']);
        
        $kategoriList = ['PAUD', 'CABERAWIT', 'PRA_REMAJA', 'REMAJA', 'PRA_NIKAH'];
        $colors = [
            'rgb(54, 162, 235)',
            'rgb(255, 99, 132)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(153, 102, 255)',
        ];

        $datasets = [];
        
        foreach ($labels as $i => $label) {
            $dataPerKategori = [];

            foreach ($kategoriList as $kategori) {
                $jumlah = Generus::where('kategori', $kategori)
                    ->whereHas('insan.desa', function ($q) use ($label) {
                        $q->where('id', $label->id);
                    })->count();
        
                $dataPerKategori[] = $jumlah;
            }

            $datasets[] = [
                'label' => $label->nm_desa,
                'data' => $dataPerKategori,
                'backgroundColor' => $colors[$i % count($colors)],
            ];    
        }

        return [
            'labels' => $kategoriList,
            'datasets' => $datasets,
        ];          
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
