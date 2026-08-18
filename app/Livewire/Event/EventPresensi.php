<?php

namespace App\Livewire\Event;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventParticipant;
use Carbon\Carbon;
use Livewire\Component;

class EventPresensi extends Component
{
    public Event $event;
    public $rfid_tag = '';
    public $lastParticipant = null;
    public $activeSessionKey = '';
    public $availableSessions = [];

    public function mount(Event $event)
    {
        $this->event = $event;

        // Extract available sessions from standardized JSON
        $sessionsData = $this->event->sessions ?? [];
        foreach ($sessionsData as $day) {
            $date = $day['date'] ?? null;
            $sesiList = $day['sesi'] ?? [];
            foreach ($sesiList as $sesi) {
                $label = $sesi['label'] ?? 'Unknown Session';
                $startTime = $sesi['start_time'] ?? null;
                $endTime = $sesi['end_time'] ?? null;
                
                $key = $date . '|' . $label;
                $display = ($date ? Carbon::parse($date)->format('d M Y') . ' - ' : '') . $label;
                
                $this->availableSessions[$key] = [
                    'display' => $display,
                    'date' => $date,
                    'label' => $label,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];
            }
        }

        if (!empty($this->availableSessions)) {
            $this->activeSessionKey = array_key_first($this->availableSessions);
        }
    }

    public function updatedRfidTag()
    {
        if (strlen($this->rfid_tag) < 4) {
            return;
        }

        if (empty($this->activeSessionKey) || !isset($this->availableSessions[$this->activeSessionKey])) {
            session()->flash('error', 'Silakan pilih Sesi Aktif terlebih dahulu.');
            $this->reset('rfid_tag');
            return;
        }

        $sessionInfo = $this->availableSessions[$this->activeSessionKey];

        $participant = EventParticipant::where('event_id', $this->event->id)
            ->where('rfid_tag', $this->rfid_tag)
            ->first();

        if (!$participant) {
            session()->flash('error', 'Peserta tidak ditemukan.');
            $this->reset('rfid_tag');
            return;
        }

        $already = Attendance::where('event_id', $this->event->id)
            ->where('participant_id', $participant->id)
            ->where('date', $sessionInfo['date'])
            ->where('session_label', $sessionInfo['label'])
            ->exists();

        if ($already) {
            session()->flash('error', 'Peserta sudah presensi di sesi ini.');
            $this->reset('rfid_tag');
            return;
        }

        $status = $this->hitungArrivalStatus();
        $message = 'Presensi berhasil: ' . ($participant->data_json['nama'] ?? '-');

        if ($status === 'over_time') {
            $message = 'Presensi berhasil, namun Anda terlambat: ' . ($participant->data_json['nama'] ?? '-');
        }

        Attendance::create([
            'event_id' => $this->event->id,
            'participant_id' => $participant->id,
            'date' => $sessionInfo['date'],
            'session_label' => $sessionInfo['label'],
            'check_in_at' => now(),
            'arrival_status' => $status,
        ]);

        $this->lastParticipant = $participant;
        session()->flash('over_time', $status === 'over_time');
        session()->flash('success', $message);
        $this->reset('rfid_tag');
        $this->dispatch('$refresh');
    }

    private function hitungArrivalStatus()
    {
        if (empty($this->activeSessionKey) || !isset($this->availableSessions[$this->activeSessionKey])) {
            return null;
        }

        $sessionInfo = $this->availableSessions[$this->activeSessionKey];
        $now = Carbon::now();
        $date = $sessionInfo['date'] ?? $now->format('Y-m-d');
        
        $start = $sessionInfo['start_time'] ? Carbon::parse($date . ' ' . $sessionInfo['start_time']) : null;
        $end = $sessionInfo['end_time'] ? Carbon::parse($date . ' ' . $sessionInfo['end_time']) : null;
        $lateTime = $start ? $start->copy()->addMinutes(10) : null;

        if ($start && $end) {
            if ($now->lt($start)) {
                return 'in_time';
            }
            if ($now->between($start, $lateTime)) {
                return 'on_time';
            }
            return 'over_time';
        }

        return null;
    }

    
    public function render()
    {
        return view('livewire.event.event-presensi');
    }
}