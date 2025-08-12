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

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function updatedRfidTag()
    {
        if (strlen($this->rfid_tag) < 4) {
            return;
        }

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
            ->exists();

        if ($already) {
            session()->flash('error', 'Peserta sudah presensi.');
            $this->reset('rfid_tag');
            return;
        }

        $status = $this->hitungArrivalStatus();
        $message = 'Presensi berhasil: ' . ($participant->data_json['nama'] ?? '-');

        // Modify the success message if the participant is late
        if ($status === 'over_time') {
            $message = 'Presensi berhasil, namun Anda terlambat: ' . ($participant->data_json['nama'] ?? '-');
        }

        Attendance::create([
            'event_id' => $this->event->id,
            'participant_id' => $participant->id,
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
        $now = Carbon::now();
        $start = $this->event->start_time ? Carbon::parse($this->event->start_time) : null;
        $end = $this->event->end_time ? Carbon::parse($this->event->end_time) : null;
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