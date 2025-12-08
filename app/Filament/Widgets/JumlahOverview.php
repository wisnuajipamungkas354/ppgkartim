<?php

namespace App\Filament\Widgets;

use App\Models\Desa;
use App\Models\Generus;
use App\Models\Kelompok;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Helpers\AccessHelper;
use App\Models\Daerah;
use App\Models\Mubaligh;
use App\Models\User;
use App\Traits\HandlesPermissionWidget;

class JumlahOverview extends BaseWidget
{
    use HandlesPermissionWidget;
    
    protected static ?int $sort = 1;

    protected static array $superAdmin      = ['total_daerah', 'total_desa', 'total_kelompok', 'total_user'];
    protected static array $phPpg           = ['total_desa', 'total_kelompok', 'total_generus', 'total_caberawit', 'total_mudamudi', 'total_mt', 'total_ms'];
    protected static array $pjpDesa         = ['total_kelompok', 'total_generus', 'total_caberawit', 'total_mudamudi', 'total_mt', 'total_ms'];
    protected static array $pjpKelompok     = ['total_generus', 'total_caberawit', 'total_mudamudi', 'total_mt', 'total_ms'];
    protected static array $mudamudiDaerah  = ['total_desa', 'total_kelompok', 'total_mudamudi',  'total_pra_remaja', 'total_remaja', 'total_pra_nikah'];
    protected static array $mudamudiDesa    = ['total_kelompok', 'total_mudamudi',  'total_pra_remaja', 'total_remaja', 'total_pra_nikah'];

    protected function getStats(): array
    {
        $fields = [];
        // Cek Role Aktif (Super Admin, Grup PPG, Grup Muda/i)
        if(AccessHelper::isSuperAdmin()) $fields = self::$superAdmin;
        elseif(AccessHelper::isPpg()) {
            $roleName = AccessHelper::getActiveRoleName();

            if($roleName == 'phppg') $fields = self::$phPpg;
            elseif($roleName == 'pjp_desa') $fields = self::$pjpDesa;
            elseif($roleName == 'pjp_kelompok') $fields = self::$pjpKelompok;
        } elseif(AccessHelper::isMudamudi()) {
            $roleName = AccessHelper::getActiveRoleName();

            if($roleName == 'mudamudi_daerah') $fields = self::$mudamudiDaerah;
            elseif($roleName == 'mudamudi_desa') $fields = self::$mudamudiDesa;
        } 

        return collect($fields)
            ->map(fn ($field) => self::getCardStats($field))
            ->filter()
            ->values()
            ->toArray();
    }

    protected function getCardStats(string $statName): ?Stat
    {
        return match($statName) {
            'total_daerah'      => Stat::make('Daerah', Daerah::count()),
            'total_desa'        => Stat::make('Desa', Desa::owned()->count()),
            'total_kelompok'    => Stat::make('Kelompok', Kelompok::owned()->count()),
            'total_generus'     => Stat::make('Generus', Generus::owned()->where('is_verified', 1)->count()),
            'total_caberawit'   => Stat::make('Caberawit', Generus::owned()->where('jenis_data', 'CBRWT')->where('is_verified', 1)->count()),
            'total_mudamudi'    => Stat::make('Muda-Mudi', Generus::owned()->where('jenis_data', 'MM')->where('is_verified', 1)->count()),
            'total_pra_remaja'  => Stat::make('Pra Remaja', Generus::owned()->where('kategori', 'PRA_REMAJA')->where('is_verified', 1)->count()),
            'total_remaja'      => Stat::make('Remaja', Generus::owned()->where('kategori', 'REMAJA')->where('is_verified', 1)->count()),
            'total_pra_nikah'   => Stat::make('Pra Nikah', Generus::owned()->where('kategori', 'PRA_NIKAH')->where('is_verified', 1)->count()),
            'total_mt'          => Stat::make('Mubaligh Tugasan', Mubaligh::owned()->where('kategori', 'MT')->count()),
            'total_ms'          => Stat::make('Mubaligh Setempat', Mubaligh::owned()->where('kategori', 'MS')->count()),
            'total_user'        => Stat::make('User', User::count()),
        };
    }
}
