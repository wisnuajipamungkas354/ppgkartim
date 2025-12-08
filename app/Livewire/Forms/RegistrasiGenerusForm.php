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
use App\Models\Minat;
use App\Models\Mubaligh;
use Carbon\Carbon;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GenerusResource\Traits\ValidateGenerusForm;
use Illuminate\Support\Str;

class RegistrasiGenerusForm extends Component implements HasForms
{
    use InteractsWithForms, ValidateGenerusForm;
    
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
                            Select::make('minat_bakat')
                                ->label('Minat & Bakat')
                                ->options(fn() => Minat::all()->pluck('nm_minat', 'slug'))
                                ->searchable()
                                ->multiple()
                                ->required(fn(Get $get) => $get('kategori') == 'PRA_NIKAH'),
                            TextInput::make('bakat_lainnya')
                                ->label('Minat & Bakat Lainnya (Jika tidak ada dalam list)')
                                ->placeholder('Masukkan disini')
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
            $rawData = $this->form->getState();

            $result = $this->validateInput($rawData, 'REGISTRASI');

            if(isset($result['bakat_lainnya']) && $result['bakat_lainnya'] !== null ) {
                $namaMinat = Str::camel($result['bakat_lainnya']);
                $slug = Str::slug($namaMinat);

                Minat::create([
                    'nm_minat' => $namaMinat,
                    'slug' => $slug
                ]);

                $data['minat_bakat'][] = $slug;
            }
            // Step 8: Simpan Insan
            $insan = Insan::create([
                'url_foto'              => $result['url_foto'] ?? null,
                'daerah_id'             => $result['daerah_id'],
                'desa_id'               => $result['desa_id'],
                'kelompok_id'           => $result['kelompok_id'] ?? null,
                'nama'                  => $result['nama'],
                'jk'                    => $result['jk'],
                'kota_lahir'            => $result['kota_lahir'],
                'tgl_lahir'             => $result['tgl_lahir'],
                'gol_dar'               => $result['gol_dar'] ?? null,
                'usia'                  => $result['usia'],
                'no_hp'                 => $result['no_hp'] ?? null,
                'pendidikan_terakhir'   => $result['pendidikan_terakhir'] ?? null,
                'jurusan'               => $result['jurusan'] ?? null,
                'dapukan'               => $result['dapukan'],
                'perkawinan'            => 'LAJANG',
                'nm_ayah'               => $result['nm_ayah'] ?? null,
                'nm_ibu'                => $result['nm_ibu'] ?? null,
                'no_hp_wali'            => $result['no_hp_wali'] ?? null,
                'minat_bakat'           => $result['minat_bakat'] ?? null,
                'siap_nikah'            => $result['siap_nikah'] ?? null,
                'detail_siap_nikah'     => $result['detail_siap_nikah'] ?? null,
                'is_mubaligh'           => $result['is_mubaligh'],
            ]);
            
            // Step 10: Simpan Data Mubaligh jika ada
            if (isset($result['mubaligh']) && $result['mubaligh'] !== 'BUKAN') {
                if ($result['mubaligh'] === 'MS') {
                    Mubaligh::create([
                        'insan_id'      => $insan->id,
                        'kategori'      => 'MS',
                        'asal_pondok'   => $result['asal_pondok'] ?? null,
                        'jml_tugas'     => $result['jml_tugas'] ?? null,
                        'lama_tugas'    => $result['lama_tugas'] ?? null,
                        'konfirmasi_kesiapan_tugas' => $result['konfirmasi_kesiapan_tugas'] ?? null,
                    ]);
                }
                unset($result['mubaligh']);
            }

            // Step 11: Simpan Generus Record
            Generus::create([
                'insan_id'          => $insan->id,
                'nis'               => $result['nis'] ?? null,
                'jenis_data'        => $result['jenis_data'],
                'kategori'          => $result['kategori'],
                'kelas_ppg_id'      => $result['kelas_ppg_id'] ?? null,
                'status_id'         => $result['status_id'] ?? null,
                'detail_status'     => $result['detail_status'] ?? null,
                'aktif_mengajar'    => $result['aktif_mengajar'] ?? false,
                'is_verified'       => $result['is_verified'],
                'riwayat_update'    => $result['riwayat_update'],
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
