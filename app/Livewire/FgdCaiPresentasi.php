<?php

namespace App\Livewire;

use App\Models\FgdGroup;
use App\Models\FgdNote;
use App\Models\FgdSession;
use App\Models\FgdTheme;
use Livewire\Component;

class FgdCaiPresentasi extends Component
{
    public $sessions = [];
    public $activeSessionId = '';
    
    public $groups = [];
    public $activeGroupId = '';

    public $themes = [];
    public $activeThemeId = '';

    public function mount()
    {
        $activeSessions = FgdSession::where('status', true)->get();
        $this->sessions = $activeSessions;
        
        if ($activeSessions->count() === 1) {
            $this->activeSessionId = $activeSessions->first()->id;
            $this->updatedActiveSessionId();
        }
    }

    public function updatedActiveSessionId()
    {
        if ($this->activeSessionId) {
            $this->groups = FgdGroup::where('fgd_session_id', $this->activeSessionId)->get();
            $this->themes = FgdTheme::where('fgd_session_id', $this->activeSessionId)->get();
        } else {
            $this->groups = [];
            $this->themes = [];
        }
        
        $this->activeGroupId = '';
        $this->activeThemeId = '';
    }

    public function render()
    {
        $note = null;
        if ($this->activeGroupId && $this->activeThemeId) {
            $note = FgdNote::where('fgd_group_id', $this->activeGroupId)
                ->where('fgd_theme_id', $this->activeThemeId)
                ->first();
        }

        return view('livewire.fgd-cai-presentasi', [
            'note' => $note
        ])->layout('components.layouts.app'); // adjust layout if needed
    }
}
