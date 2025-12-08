<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ArusGenerusArea;
use App\Filament\Widgets\GenderDoughnut;
use App\Filament\Widgets\JumlahOverview;
use App\Filament\Widgets\KategoriGenerusPie;
use App\Filament\Widgets\SensusGenerusBar;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function getColumns(): int | string | array
    {
        return [
            'md' => 3,
            'xl' => 4,
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
                JumlahOverview::class,
                ArusGenerusArea::class,
                GenderDoughnut::class,
                KategoriGenerusPie::class,
                SensusGenerusBar::class,
        ];
    }
}
