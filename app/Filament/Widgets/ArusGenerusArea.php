<?php

namespace App\Filament\Widgets;

use App\Models\Generus;
use App\Traits\HandlesPermissionWidget;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ArusGenerusArea extends ChartWidget
{
    use HandlesPermissionWidget;

    protected static ?int $sort = 4;
    protected static ?string $defaultFilter = '5';

    protected static ?string $maxHeight = '18rem';
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Arus Generus';

    protected function getFilters(): ?array
    {
        return [
            '5' => '6 Bulan',
            '11' => '12 Bulan',
        ];
    }

    protected function getData(): array
    {
        $range = $this->filter ?? static::$defaultFilter;
        
        $months = collect(range(0, $range))
            ->map(fn($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->sort()
            ->values();

        // Hitung akumulasi generus sampai bulan tersebut
        $data = $months->map(function ($month) {
            return Generus::owned()->where('is_verified', 1)->where('created_at', '<=', $month . '-31')->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Total Generus',
                    'data' => $data->toArray(),
                    'fill' => true,        // <-- Chart.js area
                    'tension' => 0.4,      // <-- sedikit kurva agar lebih smooth
                ],
            ],
            'labels' => $months->map(function ($m) {
                return Carbon::createFromFormat('Y-m', $m)->translatedFormat('M');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

}
