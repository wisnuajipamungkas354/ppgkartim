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
                    $atts = $record->attendances;
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
                $atts = $record->attendances;
                if ($atts->isEmpty()) {
                    return ['Belum Hadir'];
                }
                return $atts->map(function($att) {
                    return Carbon::parse($att->check_in_at)->format('H:i');
                })->toArray();
            })
            ->badge()
            ->color(fn(string $state) => $state === 'Belum Hadir' ? 'danger' : 'success');

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
    
    public function table(Table $table): Table
    {
        return $table
            ->query(EventParticipant::where('event_id', $this->event->id)->with('attendances'))
            ->columns($this->getTableColumns())
            ->filters(array_merge([
                SelectFilter::make('session_label')
                    ->label('Filter Sesi')
                    ->options(function () {
                        return \App\Models\Attendance::where('event_id', $this->event->id)
                            ->whereNotNull('session_label')
                            ->where('session_label', '!=', '')
                            ->distinct()
                            ->pluck('session_label', 'session_label')
                            ->toArray();
                    })
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('attendances', function (\Illuminate\Database\Eloquent\Builder $query) use ($data) {
                                $query->where('session_label', $data['value']);
                            });
                        }
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
        $participants = EventParticipant::where('event_id', $this->event->id)->get(); 
        $attendances = Attendance::where('event_id', $this->event->id)->get();
        
        $uniqueAttendeesCount = $attendances->pluck('participant_id')->unique()->count();
        $alfa = $participants->count() - $uniqueAttendeesCount;
        
        // Ambil info dari sesi pertama sebagai patokan tanggal di WA
        $firstSessionDate = $this->event->sessions[0]['date'] ?? $this->event->date;
        $date = $firstSessionDate ? Carbon::parse($firstSessionDate)->format('d/m/Y') : '-';

        $url = 'https://api.whatsapp.com/send?text=';
        $rawMessage = "*Rekap Presensi*
        %0A*{$this->event->name}*
        %0A
        %0A📆 {$date}
        %0A📍 {$this->event->place}
        %0A
        %0A*Kehadiran (Minimal 1 Sesi)*
        %0A✅ Hadir : {$uniqueAttendeesCount}
        %0A❌ Tidak Hadir : {$alfa}
        %0A👳🏻‍♀🧕 Total Peserta : {$participants->count()}
        %0A
        %0A*Total Presensi Tersimpan: {$attendances->count()}*
        %0A- In Time : {$attendances->where('arrival_status', 'in_time')->count()}
        %0A- On Time : {$attendances->where('arrival_status', 'on_time')->count()}
        %0A- Over Time : {$attendances->where('arrival_status', 'over_time')->count()}
        %0A
        %0Aالحمدلله جزاكم الله خيرا😊🙏🏻
        ";

        return $url . $rawMessage;
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
