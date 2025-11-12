<?php

namespace App\Livewire\Forms;

use App\Filament\Resources\GenerusResource\Pages\Forms\GenerusForm;
use App\Helpers\AccessHelper;
use App\Models\Daerah;
use App\Models\Dapukan;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\Insan;
use App\Models\Kelompok;
use App\Models\Status;
use App\Forms\Components\TutorialForm;
use App\Models\Mubaligh;
use Carbon\Carbon;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Builder;

class RegistrasiGenerusForm extends Component implements HasForms
{
    use InteractsWithForms;
    
    public ?array $data = [];

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Jenis Data')
                        ->schema([
                            Forms\Components\Select::make('kategori')
                            ->label('Kategori Generus')
                            ->options([
                                        'PAUD' => 'Paud/TK',
                                        'CABERAWIT' => 'Caberawit (SD)',
                                        'PRA_REMAJA' => 'Pra Remaja (SMP)',
                                        'REMAJA' => 'Remaja (SMA/K)',
                                        'PRA_NIKAH' => 'Pra Nikah (Lepas Pelajar)'
                                    ])
                            ->required()
                        ]),
                    Wizard\Step::make('Data Diri')
                        ->afterValidation(function () {
                            $data = $this->data;
                            $validated = Insan::query()->where('nama', $data['nama'])->where('jk', $data['jk'])->where('tgl_lahir', $data['tgl_lahir'])->first();
                            if ($validated) {
                                Notification::make('failed_notification')
                                ->title('Data sudah ada di database!')
                                ->body('Datamu sudah tercatat didatabase, silahkan menghubungi admin jika ada perubahan data yaa!')
                                ->danger()
                                ->color('danger')
                                ->seconds(10)
                                ->send();

                                // throw new Halt();
                            }
                        })
                        ->schema(fn(Get $get) => GenerusForm::getForms($get)),
                    Wizard\Step::make('Sambung')
                        ->schema([
                            Forms\Components\Select::make('daerah_id')
                                ->label('Daerah')
                                ->options(fn() => Daerah::query()->pluck('nm_daerah', 'id'))
                                ->afterStateUpdated(fn (Set $set) => $set('desa_id', null))
                                ->required()
                                ->live()
                                ->preload(),
                            Forms\Components\Select::make('desa_id')
                                ->label('Desa')
                                ->options(fn(Get $get) => Desa::query()->where('daerah_id', $get('daerah_id'))->pluck('nm_desa', 'id'))
                                ->required()
                                ->live()
                                ->preload(),
                            Forms\Components\Select::make('kelompok_id')
                                ->label('Kelompok')
                                ->options(fn(Get $get) => Kelompok::where('desa_id', $get('desa_id'))->pluck('nm_kelompok', 'id'))
                                ->required()
                                ->live()
                                ->preload(),
                        ]),
                    Wizard\Step::make('Orang Tua')
                        ->schema([
                            Forms\Components\TextInput::make('nm_ayah')
                                ->label('Nama Ayah')
                                ->placeholder('Masukkan nama ayah'),
                            Forms\Components\TextInput::make('nm_ibu')
                                ->label('Nama Ibu')
                                ->placeholder('Masukkan nama ibu'),
                            Forms\Components\TextInput::make('no_hp_wali')
                                ->label('Nomor HP/WhatsApp Orang Tua')
                                ->placeholder('Masukkan nomor HP/WA'),
                        ]),
                    Wizard\Step::make('Minat & Bakat')
                        ->schema([
                            Forms\Components\Select::make('minat_id')
                                ->label('Kategori Minat Bakat')
                                ->relationship('minat', 'nm_minat'),
                            Forms\Components\TextInput::make('detail_minat')
                                ->label('Sebutkan nama minat bakat'),
                        ])
                        ->visible(fn(Get $get) => !$get('kategori') == null && !in_array($get('kategori'), ['PAUD', 'CABERAWIT']))
                ])
                ->columnSpanFull()
                ->submitAction((new HtmlString(Blade::render(<<<BLADE
                <x-filament::button
                    color="success"
                    wire:click="submit"
                >
                    Submit
                </x-filament::button>
            BLADE))))
            ])
            ->statePath('data')
            ->model(Generus::class);
    }

    public function submit() 
    {
        try {
            // Step 1
            $data = $this->form->getState();
            
            // Step 2: Jenis Data
            $data['jenis_data'] = in_array($data['kategori'], ['PAUD', 'CABERAWIT']) ? 'CBRWT' : 'MM';

            // Step 3: Status
            $statusMap = [
                'PAUD' => 'paudtk',
                'CABERAWIT' => 'sd',
                'PRA_REMAJA' => 'smp',
                'REMAJA' => 'sma-smk',
            ];
            $slug = $statusMap[$data['kategori']] ?? null;
            $data['status_id'] = $slug ? Status::where('slug', $slug)->value('id') : ($data['mubaligh'] == 'MT' ? Status::where('slug', 'mt')->value('id') : null);

            // Step 4: Usia
            $data['usia'] = Carbon::parse($data['tgl_lahir'])->age ?? null;

            // Step 5: Data Terverifikasi
            $data['is_verified'] = false;
            $data['riwayat_update'] = 'DITAMBAHKAN VIA FORM REGISTRASI';

            // Step 6: Pisahkan dan kumpulkan data detail status
            $detailStatusKeys = [
                'program_studi', 'universitas', 'jabatan', 'nm_perusahaan',
                'bidang_usaha', 'nm_usaha', 'keahlian', 'nm_sekolah',
                'peminatan_sekolah', 'kelas_di_sekolah', 'is_sekolah_jm'
            ];
            $detailSiapNikah = ['tinggi_badan', 'berat_badan', 'kriteria_pasangan'];

            $detailStatusData = [];
            $detailSiapNikahData = [];

            foreach ($detailStatusKeys as $key) {
                if (isset($data[$key])) {
                    $detailStatusData[$key] = $data[$key];
                    unset($data[$key]);
                }
            }

            foreach ($detailSiapNikah as $key) {
                if(isset($data[$key])) {
                    $detailSiapNikahData[$key] = $data[$key];
                    unset($data[$key]);
                }
            }
            
            // Step 7: Menentukan Dapukan 
            $dapukanGenerus = Dapukan::where('nm_dapukan', 'GENERUS')->value('slug');
            $dapukanMubaligh = null; 
            if (isset($data['mubaligh']) && $data['mubaligh'] !== 'BUKAN') {
                if ($data['mubaligh'] === 'MT') {
                    $dapukanMubaligh = Dapukan::where('nm_dapukan', 'MUBALIGH TUGASAN')->value('slug');
                } elseif ($data['mubaligh'] === 'MS') {
                    $dapukanMubaligh = Dapukan::where('nm_dapukan', 'MUBALIGH SETEMPAT')->value('slug');
                }
            }

            // Step 8: Simpan Insan
            $insan = Insan::create([
                'url_foto' => $data['url_foto'] ?? null,
                'daerah_id' => $data['daerah_id'],
                'desa_id' => $data['desa_id'],
                'kelompok_id' => $data['kelompok_id'] ?? null,
                'nama' => $data['nama'],
                'jk' => $data['jk'],
                'kota_lahir' => $data['kota_lahir'],
                'tgl_lahir' => $data['tgl_lahir'],
                'gol_dar' => $data['gol_dar'] ?? null,
                'usia' => $data['usia'],
                'no_hp' => $data['no_hp'] ?? null,
                'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? null,
                'jurusan' => $data['jurusan'] ?? null,
                'dapukan' => [
                    $dapukanGenerus
                ],
                'perkawinan' => 'LAJANG',
                'nm_ayah' => $data['nm_ayah'] ?? null,
                'nm_ibu' => $data['nm_ibu'] ?? null,
                'no_hp_wali' => $data['no_hp_wali'] ?? null,
                'minat_id' => $data['minat_id'][0] ?? null,
                'detail_minat' => $data['detail_minat'] ?? null,
                'siap_nikah' => $data['siap_nikah'] ?? null,
                'detail_siap_nikah' => $detailSiapNikahData ?? null,
            ]);
            
            // Step 10: Simpan Data Mubaligh jika ada
            if (isset($data['mubaligh']) && $data['mubaligh'] !== 'BUKAN') {
                if ($data['mubaligh'] === 'MT') {
                    Mubaligh::create([
                        'insan_role_id' => $insan->id,
                        'tingkatan_tugas' => $data['tingkatan_tugas'] ?? null,
                        'asal_pondok' => $data['asal_pondok'] ?? null,
                        'tugasan_ke' => $data['tugasan_ke'] ?? null,
                        'tgl_mulai_tugas' => $data['tgl_mulai_tugas'] ?? null,
                    ]);
                } elseif ($data['mubaligh'] === 'MS') {
                    Mubaligh::create([
                        'insan_role_id' => $insan->id,
                        'asal_pondok' => $data['asal_pondok'] ?? null,
                        'jml_tugas' => $data['jml_tugas'] ?? null,
                        'lama_tugas' => $data['lama_tugas'] ?? null,
                    ]);
                }
                unset($data['mubaligh']);
            }

            // Step 11: Simpan Generus Record
            Generus::create([
                'insan_id' => $insan->id,
                'nis' => $data['nis'] ?? null,
                'jenis_data' => $data['jenis_data'],
                'kategori' => $data['kategori'],
                'kelas_ppg_id' => $data['kelas_ppg_id'] ?? null,
                'status_id' => $data['status_id'] ?? null,
                'detail_status' => $detailStatusData,
                'is_verified' => $data['is_verified'],
                'riwayat_update' => $data['riwayat_update'],
            ]);

            redirect('/registrasi-generus-form/');

            Notification::make('success_notification')
                ->title('Registrasi Data Berhasil!')
                ->body('Datamu akan diproses oleh admin. Alhamdulillah Jazakumullohu Khoiro! Semoga sukses lancar & barokah!')
                ->success()
                ->color('success')
                ->seconds(10)
                ->send();
        } catch (\Throwable $e) {
            // Log error jika perlu
            Log::error('Gagal simpan data generus: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make('failed')
                ->title('Gagal')
                ->danger()
                ->body('Terjadi kesalahan dalam menyimpan data, harap laporkan ini ke Super Admin')
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.forms.registrasi-generus-form');
    }
}
