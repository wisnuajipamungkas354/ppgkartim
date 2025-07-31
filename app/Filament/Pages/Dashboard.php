<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PpgCategoryPie;
use App\Filament\Widgets\PpgGenderDoughnut;
use App\Filament\Widgets\PpgSensusBar;
use App\Filament\Widgets\PpgStats;
use App\Filament\Widgets\SuperAdminStat;
use App\Helpers\AccessHelper;
use Filament\Pages\Page;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getHeaderWidgets(): array
    {
        $role = AccessHelper::getActiveRoleName();

        return match ($role) {
            'super_admin' => [
                SuperAdminStat::class,
            ],
            'phppg' => [
                PpgStats::class,
                PpgGenderDoughnut::class,
                PpgCategoryPie::class,
                PpgSensusBar::class,
            ],
            'kurikulum' => [
                // Widgets\MudaMudiStats::class,
            ],
            'tenaga_pendidik' => [
                // Widgets\MubalighWidget::class,
            ],
            'mudamudi_daerah' => [
                // Widgets\MubalighWidget::class,
            ],
            'pjp_desa' => [
                // Widgets\MubalighWidget::class,
            ],
            'mudamudi_desa' => [
                // Widgets\MubalighWidget::class,
            ],
            'pjp_kelompok' => [
                // Widgets\MubalighWidget::class,
            ],
            'mudamudi_kelompok' => [
                // Widgets\MubalighWidget::class,
            ],
            default => [],
        };
    }
}
