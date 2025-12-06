<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\GenderDoughnut;
use App\Filament\Widgets\JumlahOverview;
use App\Filament\Widgets\KategoriGenerusPie;
use App\Filament\Widgets\Ppg\PpgCategoryPie;
use App\Filament\Widgets\Ppg\PpgGenderDoughnut;
use App\Filament\Widgets\Ppg\PpgSensusBar;
use App\Filament\Widgets\Ppg\PpgStats;
use App\Filament\Widgets\SensusGenerusBar;
use App\Filament\Widgets\SuperAdmin\SuperAdminStat;
use App\Helpers\AccessHelper;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Pages\Page;
use Filament\Forms\Form;
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
                GenderDoughnut::class,
                KategoriGenerusPie::class,
                SensusGenerusBar::class,
        ];
    }
}
