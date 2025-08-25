<?php

namespace App\Livewire\Event;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventParticipant;
use Carbon\Carbon;
use Filament\Forms\Components\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
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

        $dynamicColumns[] = TextColumn::make('attendance.check_in_at')
            ->label('Jam')
            ->dateTime('H:i');
        $dynamicColumns[] = TextColumn::make('attendance.arrival_status')
            ->label('Status')
            ->badge()
            ->color(fn(string $state) => match($state) {
                'in_time' => 'info',
                'on_time' => 'success',
                'over_time' => 'warning',
                default => 'secondary',
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
    
    public function table(Table $table): Table
    {
        return $table
            ->query(EventParticipant::where('event_id', $this->event->id))
            ->columns($this->getTableColumns());
            // ->filters($this->getFilters());
    }

    public function shareOnWhatsApp() {
        $participants = EventParticipant::where('event_id', $this->event->id)->get(); 
        $attendances = Attendance::where('event_id', $this->event->id)->get();
        $alfa = $participants->count() - $attendances->count();
        $date = Carbon::parse($this->event->date)->format('d/m/Y');
        $startTime = Carbon::parse($this->event->start_time)->format('H:i');

        $url = 'https://api.whatsapp.com/send?text=';
        $rawMessage = "*Rekap Presensi*
        %0A*{$this->event->name}*
        %0A
        %0A📆 {$date}
        %0A🕒 {$startTime} s/d selesai
        %0A
        %0A*Kehadiran*
        %0A✅ Hadir : {$attendances->count()}
        %0A❌ Tidak Hadir : {$alfa}
        %0A
        %0A*Status*
        %0A- In Time : {$attendances->where('arrival_status', 'in_time')->count()}
        %0A- On Time : {$attendances->where('arrival_status', 'on_time')->count()}
        %0A- Over Time : {$attendances->where('arrival_status', 'over_time')->count()}
        %0A
        %0Aالحمدلله جزاكم الله خيرا😊🙏🏻
        ";

        return $url . $rawMessage;
    }

    public function render()
    {
        return view('livewire.event.rekap-presensi');
    }
}
