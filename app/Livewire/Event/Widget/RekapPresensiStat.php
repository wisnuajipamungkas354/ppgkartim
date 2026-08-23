<?php

namespace App\Livewire\Event\Widget;

use App\Models\Attendance;
use App\Models\EventParticipant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\Reactive;

class RekapPresensiStat extends BaseWidget
{
    public $event = null;
    
    #[Reactive]
    public $selectedSession = null;
    
    protected function getStats(): array
    {
        $participants = EventParticipant::where('event_id', $this->event->id)->get();
        
        $attendancesQuery = Attendance::where('event_id', $this->event->id);
        if ($this->selectedSession) {
            $attendancesQuery->where('session_label', $this->selectedSession);
        }
        $attendances = $attendancesQuery->get();

        $uniqueAttendeesCount = $attendances->pluck('participant_id')->unique()->count();
        $labelHadir = $this->selectedSession ? 'Peserta Hadir' : 'Peserta Hadir (Min 1 Sesi)';

        return [
            Stat::make('Total Peserta', $participants->count()),
            Stat::make($labelHadir, $uniqueAttendeesCount),
            Stat::make('Tidak Hadir', ($participants->count() - $uniqueAttendeesCount)),
            Stat::make('Total Presensi (In Time)', $attendances->where('arrival_status', 'in_time')->count()),
            Stat::make('Total Presensi (On Time)', $attendances->where('arrival_status', 'on_time')->count()),
            Stat::make('Total Presensi (Over Time)', $attendances->where('arrival_status', 'over_time')->count()),
        ];
    }
}
