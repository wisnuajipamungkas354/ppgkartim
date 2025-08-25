<?php

namespace App\Livewire\Event\Widget;

use App\Models\Attendance;
use App\Models\EventParticipant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RekapPresensiStat extends BaseWidget
{
    public $event = null;
    
    protected function getStats(): array
    {
        $participants = EventParticipant::where('event_id', $this->event->id)->get();
        $attendances = Attendance::where('event_id', $this->event->id)->get();

        return [
            Stat::make('Total Peserta', $participants->count()),
            Stat::make('Hadir', $attendances->count()),
            Stat::make('Tidak Hadir', ($participants->count() - $attendances->count())),
            Stat::make('In Time', $attendances->where('arrival_status', 'in_time')->count()),
            Stat::make('On Time', $attendances->where('arrival_status', 'on_time')->count()),
            Stat::make('Over Time', $attendances->where('arrival_status', 'over_time')->count()),
        ];
    }
}
