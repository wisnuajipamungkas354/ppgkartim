<?php

namespace App\Livewire\Event;

use App\Models\Event;
use Livewire\Component;

class PilihEvent extends Component
{
    public $search = '';

    public function render()
    {
        $events = Event::where('name', 'like', '%' . $this->search . '%')->where('is_active', true)
            ->orderByDesc('date')
            ->get();

        return view('livewire.event.pilih-event', compact('events'));
    }
}
