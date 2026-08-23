<?php

namespace App\Livewire;

use App\Models\FgdGroup;
use App\Models\FgdNote;
use App\Models\FgdSession;
use App\Models\FgdTheme;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class FgdCai extends Component implements HasForms
{
    use InteractsWithForms;

    public $sessions = [];
    public $activeSessionId = '';
    
    public $groups = [];
    public $activeGroupId = '';

    public $themes = [];
    public $activeThemeId = '';

    public ?array $data = [];
    public $isSubmitted = false;

    public function mount()
    {
        $activeSessions = FgdSession::where('status', true)->get();
        $this->sessions = $activeSessions;
        
        if ($activeSessions->count() === 1) {
            $this->activeSessionId = $activeSessions->first()->id;
            $this->updatedActiveSessionId();
        }

        $this->form->fill();
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
        $this->isSubmitted = false;
        $this->loadExistingNote();
    }

    public function updatedActiveGroupId()
    {
        $this->loadExistingNote();
    }

    public function updatedActiveThemeId()
    {
        $this->loadExistingNote();
    }

    public function loadExistingNote()
    {
        $this->isSubmitted = false;

        if ($this->activeGroupId && $this->activeThemeId) {
            $existing = FgdNote::where('fgd_group_id', $this->activeGroupId)
                ->where('fgd_theme_id', $this->activeThemeId)
                ->first();

            if ($existing) {
                $this->form->fill($existing->toArray());
            } else {
                $this->form->fill();
            }
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('INFORMASI NOTULIS')
                    ->schema([
                        Forms\Components\Placeholder::make('sesi_fgd')
                            ->label('Sesi FGD')
                            ->content(fn () => collect($this->themes)->firstWhere('id', $this->activeThemeId)?->title ?? '-'),
                        Forms\Components\TextInput::make('notulis_name')
                            ->label('Nama Notulis / Pengisi (Opsional)')
                            ->placeholder('Contoh: Ahmad F...'),
                    ])->columns(2),

                Forms\Components\Section::make('PROBLEM - PENYEBAB - SOLUSI')
                    ->schema([
                        Forms\Components\RichEditor::make('problem')->label('Problem')->placeholder('Tuliskan problem utama...'),
                        Forms\Components\RichEditor::make('penyebab')->label('Penyebab')->placeholder('Tuliskan akar penyebab...'),
                        Forms\Components\RichEditor::make('solusi')->label('Solusi')->placeholder('Tuliskan usulan solusi...'),
                    ])->columns(1),

                Forms\Components\Section::make('ACTION PLAN')
                    ->schema([
                        Forms\Components\RichEditor::make('ap_deskripsi')->label('Deskripsi')->placeholder('Tuliskan deskripsi action plan...'),
                        Forms\Components\RichEditor::make('ap_nama_kegiatan')->label('Nama Kegiatan')->placeholder('Tuliskan nama kegiatan...'),
                        Forms\Components\RichEditor::make('ap_peserta')->label('Peserta')->placeholder('Tuliskan siapa saja pesertanya...'),
                        Forms\Components\RichEditor::make('ap_waktu')->label('Waktu')->placeholder('Tuliskan target waktu pelaksanaan...'),
                        Forms\Components\RichEditor::make('ap_dana')->label('Dana')->placeholder('Tuliskan rincian kebutuhan dana...'),
                    ])->columns(1),
            ])
            ->statePath('data');
    }

    public function submitFgdNote()
    {
        $this->validate([
            'activeGroupId' => 'required',
            'activeThemeId' => 'required',
        ]);

        $formData = $this->form->getState();

        FgdNote::updateOrCreate(
            [
                'fgd_group_id' => $this->activeGroupId,
                'fgd_theme_id' => $this->activeThemeId,
            ],
            [
                'notulis_name' => $formData['notulis_name'] ?? null,
                'problem' => $formData['problem'] ?? null,
                'penyebab' => $formData['penyebab'] ?? null,
                'solusi' => $formData['solusi'] ?? null,
                'ap_deskripsi' => $formData['ap_deskripsi'] ?? null,
                'ap_nama_kegiatan' => $formData['ap_nama_kegiatan'] ?? null,
                'ap_peserta' => $formData['ap_peserta'] ?? null,
                'ap_waktu' => $formData['ap_waktu'] ?? null,
                'ap_dana' => $formData['ap_dana'] ?? null,
                'peran_keimaman' => $formData['peran_keimaman'] ?? null,
                'peran_pengurus' => $formData['peran_pengurus'] ?? null,
                'peran_orang_tua' => $formData['peran_orang_tua'] ?? null,
                'peran_mubaligh' => $formData['peran_mubaligh'] ?? null,
                'peran_ahli_pendidik' => $formData['peran_ahli_pendidik'] ?? null,
            ]
        );

        $this->isSubmitted = true;
        // Do not clear the form so they can continue editing if they bypass the success message
        // $this->form->fill();
    }

    public function render()
    {
        // Temukan nama grup aktif untuk judul
        $activeGroupName = 'Grup';
        if ($this->activeGroupId) {
            $group = collect($this->groups)->firstWhere('id', $this->activeGroupId);
            if ($group) $activeGroupName = $group->name;
        }

        return view('livewire.fgd-cai', [
            'activeGroupName' => $activeGroupName
        ]); // Hapus layout jika project ini pakai Filament standalone pages atau native layout injection
    }
}
