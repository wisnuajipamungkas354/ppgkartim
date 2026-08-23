<?php

namespace App\Livewire\Event;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventParticipant;
use Carbon\Carbon;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;

class RekapPresensi extends Component implements HasForms, HasTable
{  
    use InteractsWithTable, InteractsWithForms;

    public Event $event;

    public function mount(Event $event) {
        $this->event = $event;
    }

    public function getTableColumns(): array {
        $config = $this->event->column_config;
        $dynamicColumns = [];

        $dynamicColumns[] = TextColumn::make('rfid_tag')
            ->label('RFID Tag');
        foreach($config as $c) {
            if($c['type'] != 'boolean') {
                $dynamicColumns[] = TextColumn::make('data_json.' . $c['field'])
                    ->label($c['label'])
                    ->sortable()
                    ->searchable($c['searchable'] ?? false);
                    // ->toggleable(isToggledHiddenByDefault: $c['hidden']);
            } else {
                $dynamicColumns[] = ToggleColumn::make('data_json.' . $c['field'])
                    ->label($c['label'])
                    ->sortable()
                    ->searchable($c['searchable'] ?? false)
                    ->toggleable(isToggledHiddenByDefault: $c['hidden'] ?? false);
            }
        }

        if ($this->event->event_type !== 'single') {
            $dynamicColumns[] = TextColumn::make('nama_sesi')
                ->label('Sesi Kehadiran')
                ->getStateUsing(function (EventParticipant $record) {
                    $selectedSession = $this->getTableFilterState('session_label')['value'] ?? null;
                    $atts = $record->attendances;
                    
                    if ($selectedSession) {
                        $atts = $atts->filter(fn($att) => $att->session_label === $selectedSession);
                    }

                    if ($atts->isEmpty()) {
                        return ['-'];
                    }
                    return $atts->map(function($att) {
                        return $att->session_label ?: 'Sesi Tunggal';
                    })->toArray();
                })
                ->badge()
                ->color(fn(string $state) => $state === '-' ? 'danger' : 'primary');
        }

        $dynamicColumns[] = TextColumn::make('jam_hadir')
            ->label('Jam Presensi')
            ->getStateUsing(function (EventParticipant $record) {
                $selectedSession = $this->getTableFilterState('session_label')['value'] ?? null;
                $atts = $record->attendances;
                
                if ($selectedSession) {
                    $atts = $atts->filter(fn($att) => $att->session_label === $selectedSession);
                }

                if ($atts->isEmpty()) {
                    return ['Belum Hadir'];
                }
                return $atts->map(function($att) {
                    return Carbon::parse($att->check_in_at)->format('H:i');
                })->toArray();
            })
            ->badge()
            ->color(fn(string $state) => $state === 'Belum Hadir' ? 'danger' : 'success')
            ->sortable(query: function (\Illuminate\Database\Eloquent\Builder $query, string $direction) {
                $selectedSession = $this->getTableFilterState('session_label')['value'] ?? null;
                
                return $query->orderBy(
                    \App\Models\Attendance::select('check_in_at')
                        ->whereColumn('attendances.participant_id', 'event_participants.id')
                        ->when($selectedSession, fn($q) => $q->where('session_label', $selectedSession))
                        ->orderBy('check_in_at', 'asc')
                        ->limit(1),
                    $direction
                );
            });

        return $dynamicColumns;
    }

    public function getTableFilters(): array {
        $config = $this->event->column_config;
        $filters = [];

        foreach($config as $c) {
            if(!empty($c['filterable']) && $c['filterable'] == true) {
                $filters[] = SelectFilter::make('data_json.' . $c['field'])
                    ->label($c['label'])
                    ->options(function() use($c) {
                        $options = [];
                        foreach($c['options'] as $opt) {
                            $options[$opt] = $opt;
                        }

                        return $options;
                    });
            }
        }

        return $filters;
    }
    
    public function getDefaultSessionLabel()
    {
        if ($this->event->event_type === 'single') {
            return null;
        }

        $now = Carbon::now();
        $todayStr = $now->format('Y-m-d');

        $firstSession = null;
        $lastSession = null;

        $sessions = $this->event->sessions ?? [];
        $normalizedDays = [];
        if ($this->event->event_type === 'multi_session') {
            if (isset($sessions[0]['date']) && isset($sessions[0]['sesi'])) {
                $normalizedDays = $sessions;
            } else {
                $normalizedDays = [['date' => $this->event->date, 'sesi' => $sessions]];
            }
        } elseif ($this->event->event_type === 'multi_day') {
            $normalizedDays = $sessions;
        }

        foreach ($normalizedDays as $day) {
            $sessionDate = $day['date'] ?? $this->event->date;
            
            if (!empty($day['sesi']) && is_array($day['sesi'])) {
                foreach ($day['sesi'] as $sesi) {
                    $label = $sesi['label'] ?? null;
                    if (!$label) continue;

                    if (!$firstSession) $firstSession = $label;
                    $lastSession = $label;

                    if ($sessionDate === $todayStr) {
                        $end = Carbon::parse($sesi['end_time'] ?? '23:59');
                        $end->setDateFrom(Carbon::parse($sessionDate));
                        
                        $start = Carbon::parse($sesi['start_time'] ?? '00:00');
                        if ($end->lessThan($start)) {
                            $end->addDay();
                        }

                        if ($now->lessThanOrEqualTo($end)) {
                            return $label;
                        }
                    }
                }
            }
        }

        return $lastSession ?? $firstSession;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(EventParticipant::where('event_id', $this->event->id)->with('attendances'))
            ->columns($this->getTableColumns())
            ->filters(array_merge([
                SelectFilter::make('kehadiran')
                    ->label('Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'tidak_hadir' => 'Tidak Hadir',
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $selectedSession = $this->getTableFilterState('session_label')['value'] ?? null;
                            if ($data['value'] === 'hadir') {
                                $query->whereHas('attendances', function ($q) use ($selectedSession) {
                                    if ($selectedSession) $q->where('session_label', $selectedSession);
                                });
                            } elseif ($data['value'] === 'tidak_hadir') {
                                $query->whereDoesntHave('attendances', function ($q) use ($selectedSession) {
                                    if ($selectedSession) $q->where('session_label', $selectedSession);
                                });
                            }
                        }
                    }),
                SelectFilter::make('session_label')
                    ->label('Filter Sesi')
                    ->options(function () {
                        $options = [];
                        
                        $sessions = $this->event->sessions ?? [];
                        $normalizedDays = [];
                        if ($this->event->event_type === 'multi_session') {
                            if (isset($sessions[0]['date']) && isset($sessions[0]['sesi'])) {
                                $normalizedDays = $sessions;
                            } else {
                                $normalizedDays = [['date' => $this->event->date, 'sesi' => $sessions]];
                            }
                        } elseif ($this->event->event_type === 'multi_day') {
                            $normalizedDays = $sessions;
                        }
                        
                        foreach ($normalizedDays as $day) {
                            if (!empty($day['sesi']) && is_array($day['sesi'])) {
                                foreach ($day['sesi'] as $sesi) {
                                    if (!empty($sesi['label'])) {
                                        $options[$sesi['label']] = $sesi['label'];
                                    }
                                }
                            }
                        }

                        $dbSessions = \App\Models\Attendance::where('event_id', $this->event->id)
                            ->whereNotNull('session_label')
                            ->where('session_label', '!=', '')
                            ->distinct()
                            ->pluck('session_label')
                            ->toArray();
                            
                        foreach($dbSessions as $dbSesi) {
                            $options[$dbSesi] = $dbSesi;
                        }

                        return $options;
                    })
                    ->default(fn() => $this->getDefaultSessionLabel())
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        // Do not filter the participants query here, so we can still see absentees!
                        // The session_label state is used by the Kehadiran filter and table columns.
                    })
            ], $this->getTableFilters()))
            ->actions([
                Action::make('delete')
                    ->label('Reset')
                    ->action(fn(EventParticipant $record) => Attendance::where('participant_id', $record->id)->delete())
                    ->successNotification(
                        Notification::make()
                        ->success()
                        ->title('Berhasil di reset')
                    )
            ])
            ->poll('5s');
    }

    public function shareOnWhatsApp() {
        $selectedSession = $this->getTableFilterState('session_label')['value'] ?? null;
        
        $participants = EventParticipant::where('event_id', $this->event->id)->get(); 
        
        $attendancesQuery = Attendance::where('event_id', $this->event->id);
        if ($selectedSession) {
            $attendancesQuery->where('session_label', $selectedSession);
        }
        $attendances = $attendancesQuery->get();
        
        $uniqueAttendeesCount = $attendances->pluck('participant_id')->unique()->count();
        $alfa = $participants->count() - $uniqueAttendeesCount;
        
        // Ambil info dari sesi pertama sebagai patokan tanggal di WA
        $firstSessionDate = $this->event->sessions[0]['date'] ?? $this->event->date;
        $date = $firstSessionDate ? Carbon::parse($firstSessionDate)->format('d/m/Y') : '-';
        
        $sessionTitle = $selectedSession ? "Sesi: {$selectedSession}" : "Semua Sesi";

        $nameField = null;
        foreach ($this->event->column_config as $c) {
            if (str_contains(strtolower($c['field']), 'nama') || str_contains(strtolower($c['label']), 'nama')) {
                $nameField = $c['field'];
                break;
            }
        }
        if (!$nameField && !empty($this->event->column_config[0])) {
            $nameField = $this->event->column_config[0]['field'];
        }

        $earliest = Attendance::where('event_id', $this->event->id)
            ->when($selectedSession, fn($q) => $q->where('session_label', $selectedSession))
            ->with('participant')
            ->orderBy('check_in_at', 'asc')
            ->limit(3)
            ->get();

        $latest = Attendance::where('event_id', $this->event->id)
            ->when($selectedSession, fn($q) => $q->where('session_label', $selectedSession))
            ->with('participant')
            ->orderBy('check_in_at', 'desc')
            ->limit(3)
            ->get();

        $rawMessage = "*Rekap Presensi*\n"
            . "*{$this->event->name}*\n\n"
            . "📆 {$date}\n"
            . "📍 {$this->event->place}\n"
            . "🏷 {$sessionTitle}\n\n"
            . "*Kehadiran*\n"
            . "✅ Hadir : {$uniqueAttendeesCount}\n"
            . "❌ Tidak Hadir : {$alfa}\n"
            . "👳🏻‍♀🧕 Total Peserta : {$participants->count()}\n\n"
            . "*Detail Keterlambatan:*\n"
            . "- In Time : {$attendances->where('arrival_status', 'in_time')->count()}\n"
            . "- On Time : {$attendances->where('arrival_status', 'on_time')->count()}\n"
            . "- Over Time : {$attendances->where('arrival_status', 'over_time')->count()}\n\n"
            . "*🏆 3 Peserta Paling Awal:*\n"
            . $this->formatTopAttendees($earliest, $nameField) . "\n\n"
            . "*🏃 3 Peserta Paling Akhir:*\n"
            . $this->formatTopAttendees($latest, $nameField) . "\n\n";

        $absentParticipantIds = $participants->pluck('id')->diff($attendances->pluck('participant_id'));
        $absentParticipants = $participants->whereIn('id', $absentParticipantIds)->values();

        if ($absentParticipants->isNotEmpty()) {
            $rawMessage .= "*❌ Peserta Tidak Hadir:*\n";
            $limit = min(10, $absentParticipants->count());
            for ($i = 0; $i < $limit; $i++) {
                $name = $absentParticipants[$i]->data_json[$nameField] ?? 'Tanpa Nama';
                $rawMessage .= ($i + 1) . ". {$name}\n";
            }
            if ($absentParticipants->count() > 10) {
                $rekapUrl = route('events.rekap', $this->event->hash_id ?? $this->event->id);
                $rawMessage .= "11. Lihat selengkapnya di {$rekapUrl}\n";
            }
            $rawMessage .= "\n";
        }

        $rawMessage .= "الحمدلله جزاكم الله خيرا😊🙏🏼";

        // Ganti \n dengan %0A dan replace spasi biasa untuk URL encode yang bersih
        return 'https://api.whatsapp.com/send?text=' . str_replace('%250A', '%0A', urlencode(str_replace("\n", "%0A", $rawMessage)));
    }

    private function formatTopAttendees($attendancesList, $nameField) {
        if ($attendancesList->isEmpty()) {
            return "- Belum ada data -";
        }
        $result = [];
        foreach ($attendancesList as $index => $att) {
            $name = $att->participant->data_json[$nameField] ?? 'Tanpa Nama';
            $time = Carbon::parse($att->check_in_at)->format('H:i');
            $rank = $index + 1;
            $result[] = "{$rank}. {$name} ({$time})";
        }
        return implode("\n", $result);
    }

    public function getEventScheduleDisplayProperty()
    {
        $selectedSession = $this->getTableFilterState('session_label')['value'] ?? null;

        $dateStr = '';
        $timeStr = '';

        if ($this->event->event_type === 'single') {
            $dateStr = \Carbon\Carbon::parse($this->event->date)->locale('id')->translatedFormat('d F Y');
            $timeStr = \Carbon\Carbon::parse($this->event->start_time)->format('H:i') . ' s/d ' . \Carbon\Carbon::parse($this->event->end_time)->format('H:i');
        } else {
            $found = false;
            
            // Jika ada sesi spesifik yang di-filter
            if ($selectedSession) {
                foreach ($this->event->sessions ?? [] as $day) {
                    if (isset($day['sesi'])) { // multi_day
                        foreach ($day['sesi'] as $sesi) {
                            if ($sesi['label'] === $selectedSession) {
                                $dateStr = \Carbon\Carbon::parse($day['date'] ?? now())->locale('id')->translatedFormat('d F Y');
                                $timeStr = \Carbon\Carbon::parse($sesi['start_time'])->format('H:i') . ' s/d ' . \Carbon\Carbon::parse($sesi['end_time'])->format('H:i');
                                $found = true;
                                break 2;
                            }
                        }
                    } else { // multi_session
                        if ($day['label'] === $selectedSession) {
                            $dateStr = \Carbon\Carbon::parse($this->event->date ?? now())->locale('id')->translatedFormat('d F Y');
                            $timeStr = \Carbon\Carbon::parse($day['start_time'])->format('H:i') . ' s/d ' . \Carbon\Carbon::parse($day['end_time'])->format('H:i');
                            $found = true;
                            break;
                        }
                    }
                }
            }

            // Jika tidak ada filter sesi (Semua Sesi), atau filter tidak ditemukan
            if (!$found) {
                $dates = [];
                if ($this->event->event_type === 'multi_day') {
                    foreach ($this->event->sessions ?? [] as $day) {
                        if (!empty($day['date'])) {
                            $dates[] = $day['date'];
                        }
                    }
                }
                
                if (empty($dates)) {
                    $dates[] = $this->event->date ?? now()->toDateString();
                }

                sort($dates);
                $startDate = \Carbon\Carbon::parse($dates[0])->locale('id');
                $endDate = \Carbon\Carbon::parse(end($dates))->locale('id');

                if ($startDate->isSameDay($endDate)) {
                    $dateStr = $startDate->translatedFormat('d F Y');
                } elseif ($startDate->isSameMonth($endDate)) {
                    $dateStr = $startDate->format('d') . ' - ' . $endDate->translatedFormat('d F Y');
                } elseif ($startDate->isSameYear($endDate)) {
                    $dateStr = $startDate->translatedFormat('d F') . ' - ' . $endDate->translatedFormat('d F Y');
                } else {
                    $dateStr = $startDate->translatedFormat('d F Y') . ' - ' . $endDate->translatedFormat('d F Y');
                }

                $timeStr = ""; // Tidak perlu menampilkan jam jika semua sesi
            }
        }

        $display = $dateStr;
        if ($timeStr !== '') {
            $display .= ' | ' . $timeStr;
        }
        
        if ($selectedSession) {
            $display .= ' | Filter: ' . $selectedSession;
        } elseif ($this->event->event_type !== 'single') {
            $display .= ' | Semua Sesi';
        }

        return $display;
    }

    public function render()
    {
        return view('livewire.event.rekap-presensi');
    }
}
