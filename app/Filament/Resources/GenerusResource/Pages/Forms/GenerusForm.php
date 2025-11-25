<?php

namespace App\Filament\Resources\GenerusResource\Pages\Forms;

use App\Models\Status;
use App\Models\KelasPpg;
use Filament\Forms\Get;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class GenerusForm
{
    protected static array $categoryFields = [
        'PAUD' => ['nama', 'jk', 'kota_lahir', 'tgl_lahir', 'gol_dar', 'nm_sekolah', 'is_sekolah_jm'],
        'CABERAWIT' => ['nama', 'jk', 'kota_lahir', 'tgl_lahir', 'gol_dar', 'kelas_di_sekolah', 'nm_sekolah', 'is_sekolah_jm'],
        'PRA_REMAJA' => ['nama', 'jk', 'kota_lahir', 'tgl_lahir', 'gol_dar', 'kelas_di_sekolah', 'nm_sekolah', 'is_sekolah_jm', 'mubaligh', 'asal_pondok', 'jml_tugas', 'lama_tugas', 'konfirmasi_kesiapan_tugas'],
        'REMAJA' => ['nama', 'jk', 'kota_lahir', 'tgl_lahir', 'gol_dar', 'nm_sekolah', 'kelas_di_sekolah', 'peminatan_sekolah', 'no_hp', 'mubaligh', 'tingkatan_tugas', 'asal_pondok', 'jml_tugas', 'lama_tugas', 'konfirmasi_kesiapan_tugas'],
        'PRA_NIKAH' => ['nama', 'jk', 'kota_lahir', 'tgl_lahir', 'gol_dar', 'pendidikan_terakhir', 'jurusan', 'mubaligh', 'asal_pondok', 'jml_tugas', 'lama_tugas', 'konfirmasi_kesiapan_tugas', 'status_id', 'program_studi', 'universitas', 'jabatan', 'nm_perusahaan', 'bidang_usaha', 'nm_usaha', 'keahlian', 'no_hp', 'siap_nikah', 'tinggi_badan', 'berat_badan', 'kriteria_pasangan'],
    ];

    protected static array $categoryOptions = [
        'PAUD' => [
            'placeholderSekolah' => 'Masukkan nama sekolah Paud/TK',
        ],
        'CABERAWIT' => [
            'placeholderSekolah' => 'Masukkan nama sekolah SD',
            'kelasDiSekolah' => [
                1 => 'Kelas 1', 2 => 'Kelas 2', 3 => 'Kelas 3',
                4 => 'Kelas 4', 5 => 'Kelas 5', 6 => 'Kelas 6',
            ],
        ],
        'PRA_REMAJA' => [
            'placeholderSekolah' => 'Masukkan nama sekolah SMP',
            'kelasDiSekolah' => [
                7 => 'Kelas 7', 8 => 'Kelas 8', 9 => 'Kelas 9',
            ],
        ],
        'REMAJA' => [
            'placeholderSekolah' => 'Masukkan nama sekolah SMA/K',
            'kelasDiSekolah' => [
                10 => 'Kelas 10', 11 => 'Kelas 11', 12 => 'Kelas 12',
            ],
        ],
        'PRA_NIKAH' => [
            'listStatus' => [],
        ],
    ];

    public static function getForms(Get $get): array
    {
        $kategori = $get('kategori');
        $fields = self::$categoryFields[$kategori] ?? [];
        $options = self::$categoryOptions[$kategori] ?? [];

        if ($kategori === 'PRA_NIKAH') {
            self::$categoryOptions['PRA_NIKAH']['listStatus'] = Status::all()
                ->whereNotIn('slug', ['paudtk', 'sd', 'smp', 'sma-smk', 'mt'])
                ->pluck('nm_status', 'id')
                ->toArray();
        }

        return collect($fields)
            ->map(fn ($field) => self::getGeneralFields($field, $options, $get) ?? self::getMubalighFields($field, $options, $get) ?? self::getDetailStatusFields($field, $options, $get))
            ->filter()
            ->values()
            ->toArray();
    }

    protected static function getGeneralFields(string $field, array $options, Get $get): ?Forms\Components\Component
    {
        return match ($field) {
            'nama' => Forms\Components\TextInput::make('nama')
                ->label('Nama Lengkap')
                ->placeholder('Masukkan Nama Lengkap')
                ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                ->maxLength(255)
                ->required()
                ->columnSpanFull(),
            'jk' => Forms\Components\Radio::make('jk')
                ->label('Jenis Kelamin')
                ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                ->required(),
            'kota_lahir' => Forms\Components\TextInput::make('kota_lahir')
                ->label('Kota Lahir')
                ->placeholder('Kota Lahir')
                ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                ->required()
                ->maxLength(255),
            'tgl_lahir' => Forms\Components\DatePicker::make('tgl_lahir')
                ->displayFormat('d/m/Y')
                ->label('Tanggal Lahir')
                ->maxDate(now()->format('Y-m-d'))
                ->required(),
            'pendidikan_terakhir' => Forms\Components\Select::make('pendidikan_terakhir')
                ->label('Pendidikan Terakhir')
                ->options(fn() => Status::query()->whereIn('slug', ['paudtk', 'sd', 'smp', 'sma-smk', 'd3', 's1-d4', 's2', 's3'])->pluck('nm_status', 'slug')->toArray())
                ->live()
                ->afterStateUpdated(fn(Get $get) => null)
                ->required(),
            'jurusan' => Forms\Components\Textarea::make('jurusan')
                ->label('Jurusan/Program Studi Pendidikan Terakhir')
                ->placeholder('Masukkan Jurusan/Program Studi Pendidikan Terakhir, Contoh: Manajemen')
                ->visible(fn(Get $get) => !in_array($get('pendidikan_terakhir'), ['paudtk','sd','smp'])),
            'gol_dar' => Forms\Components\Select::make('gol_dar')
                ->label('Golongan Darah')
                ->options(['A' => 'A', 'B' => 'B', 'O' => 'O', 'AB' => 'AB']),
            'kelas_ppg_id' => Forms\Components\Select::make('kelas_ppg_id')
                ->label('Kelas di PPG')
                ->options(fn() => KelasPpg::all()->pluck('nm_kelas', 'id')),
            'no_hp' => Forms\Components\TextInput::make('no_hp')
                ->label('No HP/WhatsApp')
                ->numeric()
                ->placeholder('Masukkan nomor HP/WA, Contoh: 085XXX')
                ->required(fn(Get $get) => $get('kategori') == 'PRA_NIKAH'),
            'siap_nikah' => Forms\Components\Radio::make('siap_nikah')
                ->label('Siap Nikah')
                ->options(['BELUM' => 'Belum Siap', 'SIAP' => 'Siap Nikah'])
                ->descriptions(['BELUM' => 'Belum siap menikah', 'SIAP' => 'Siap Nikah berarti siap untuk dilancarkan, dicarikan, dan ditaaruf/dikenalkan oleh Pengurus PNKB'])
                ->live()
                ->required(),
            'mubaligh' => Forms\Components\Radio::make('mubaligh')
                ->label('Apakah Anda Seorang Mubaligh ?')
                ->options(['MS' => 'Ya, Mubaligh Setempat (MS)', 'BUKAN' => 'Bukan Mubaligh'])
                ->live()
                ->afterStateUpdated(fn(Get $get) => null)
                ->required(),
            'status_id' => Forms\Components\Select::make('status_id')
                ->label('Status Saat Ini')
                ->options($options['listStatus'] ?? [])
                ->live()
                ->required()
                ->afterStateUpdated(fn(Get $get) => null) // To refresh other fields based on the selected status_id
                ->visible(fn(Get $get) => $get('mubaligh') != 'MT'),
            default => null,
        };
    }

    protected static function getMubalighFields(string $field, array $options, Get $get): ?Forms\Components\Component
    {
        return match ($field) {
            'tingkatan_tugas' => Forms\Components\Select::make('tingkatan_tugas')
                ->label('Tingkatan Tugas')
                ->options(['KELOMPOK' => 'Kelompok', 'DESA' => 'Desa', 'DAERAH' => 'Daerah', 'PONDOK' => 'Pondok'])
                ->required()
                ->visible(fn(Get $get) => $get('mubaligh') == 'MT'),
            'asal_pondok' => Forms\Components\TextInput::make('asal_pondok')
                ->label('Asal Pondok')
                ->placeholder('Masukkan nama pondok, Contoh : Ponpes Baitul Ulya')
                ->required()
                ->visible(fn(Get $get) => $get('mubaligh') == 'MS'), // Only visible when Mubaligh is selected
            'tugasan_ke' => Forms\Components\TextInput::make('tugasan_ke')
                ->label('Saat ini tugasan yang ke berapa ?')
                ->numeric()
                ->placeholder('Masukkan jumlah tugasan saat ini')
                ->required()
                ->visible(fn(Get $get) => $get('mubaligh') == 'MT'),
            'tgl_mulai_tugas' => Forms\Components\DatePicker::make('tgl_mulai_tugas')
                ->displayFormat('d/m/Y')
                ->label('Tanggal Mulai Tugas')
                ->maxDate(now()->format('Y-m-d'))
                ->required()
                ->visible(fn(Get $get) => $get('mubaligh') == 'MT'),
            'jml_tugas' => Forms\Components\TextInput::make('jml_tugas')
                ->label('Berapa kali kamu melaksanakan tugas ?')
                ->numeric()
                ->placeholder('Masukkan jumlah tugas')
                ->suffix('kali')
                ->required()
                ->visible(fn(Get $get) => $get('mubaligh') == 'MS'),
            'lama_tugas' => Forms\Components\Select::make('lama_tugas')
                ->label('Berapa total tahun kamu melaksanakan tugas ?')
                ->options([
                    'Belum pernah tugas' => 'Belum pernah tugas',
                    'Dibawah 1 tahun' => 'Kurang dari 1 tahun',
                    '1 - 2 tahun' => '1 - 2 tahun',
                    '2 - 3 tahun' => '2 - 3 tahun',
                    '3 - 4 tahun' => '3 - 4 tahun',
                    '4 - 5 tahun' => '4 - 5 tahun',
                    '5 tahun ke atas' => '5 tahun ke atas',
                ])
                ->required()
                ->visible(fn(Get $get) => $get('mubaligh') == 'MS'),
            'konfirmasi_kesiapan_tugas' => Forms\Components\Select::make('konfirmasi_kesiapan_tugas')
                ->label('Apakah kamu siap untuk melaksanakan tugas lagi?')
                ->options([
                    'Secepatnya' => 'Ya, secepatnya',
                    'Santai' => 'Ya, namun santai',
                    'Tidak' => 'Tidak, saya tidak ada rencana untuk tugas lagi',
                ])
                ->required()
                ->visible(fn(Get $get) => $get('mubaligh') == 'MS'),
            default => null
        };
    }

    protected static function getDetailStatusFields(string $field, array $options, Get $get): ?Forms\Components\Component
    {
        // Get the selected status slug from the database
        $selectedStatusSlug = Status::find($get('status_id'))->slug ?? null;

        return match ($field) {
            'nm_sekolah' => Forms\Components\TextInput::make('nm_sekolah')
                ->label('Nama Sekolah')
                ->placeholder($options['placeholderSekolah'] ?? '')
                ->required(),
            'is_sekolah_jm' => Forms\Components\CheckBox::make('is_sekolah_jm')
                ->label("Sekolah tersebut adalah Sekolah Jama'ah"),
            'peminatan_sekolah' => Forms\Components\TextInput::make('peminatan_sekolah')
                ->label('Peminatan/Jurusan Di Sekolah')
                ->placeholder('Contoh: IPA, Teknik Komputer & Jaringan, dll')
                ->required(),
            'kelas_di_sekolah' => Forms\Components\Select::make('kelas_di_sekolah')
                ->label('Kelas Di Sekolah')
                ->options($options['kelasDiSekolah'] ?? [])
                ->required(),
            'program_studi' => Forms\Components\TextInput::make('program_studi')
                ->label('Program Studi')
                ->placeholder('Masukkan Nama Program Studi')
                ->required()
                ->visible(fn(Get $get) => in_array(Status::find($get('status_id'))?->slug, ['d3','s1-d4','s2','s3','kuliah-kerja']) ?? false),
            'universitas' => Forms\Components\TextInput::make('universitas')
                ->label('Nama Perguruan Tinggi')
                ->placeholder('Masukkan Nama Perguruan Tinggi')
                ->required()
                ->visible(fn(Get $get) => in_array(Status::find($get('status_id'))?->slug, ['s1-d4','s2','s3','kuliah-kerja']) ?? false),
            'jabatan' => Forms\Components\TextInput::make('jabatan')
                ->label('Nama Posisi/Jabatan')
                ->placeholder('Contoh: Operator Produksi')
                ->required()
                ->visible(fn(Get $get) => in_array(Status::find($get('status_id'))?->slug, ['karyawan-pegawai', 'kuliah-kerja']) ?? false),
            'nm_perusahaan' => Forms\Components\TextInput::make('nm_perusahaan')
                ->label('Nama Perusahaan')
                ->placeholder('Contoh: PT. Barokah Sejahtera')
                ->required()
                ->visible(fn(Get $get) => in_array(Status::find($get('status_id'))?->slug, ['karyawan-pegawai', 'kuliah-kerja']) ?? false),
            'bidang_usaha' => Forms\Components\Select::make('bidang_usaha')
                ->label('Bidang Usaha')
                ->options([
                    'PERDAGANGAN' => 'Perdagangan (Jualan Makanan, Barang, dll.)',
                    'JASA' => 'Jasa (Servis Elektronik, Ojek Online, Jahit, dll.)',
                    'TEKNOLOGI' => 'Teknologi (Pembuatan Aplikasi, Afiliator, Konten Kreator dll.)',
                    'KREATIF' => 'Kreatif (Kerajinan, Fotografer, Desain Grafis, Editor Video dll.)',
                    'PERTANIAN' => 'Pertanian, Peternakan dan Perikanan',
                ])
                ->required()
                ->visible(fn(Get $get) => Status::find($get('status_id'))?->slug === 'wirausaha-freelance' ?? false),
            'nm_usaha' => Forms\Components\TextInput::make('nm_usaha')
                ->label('Nama Usaha')
                ->placeholder('Contoh: PT. Barokah Sejahtera')
                ->required()
                ->visible(fn(Get $get) => Status::find($get('status_id'))?->slug === 'wirausaha-freelance' ?? false),
            'keahlian' => Forms\Components\TextInput::make('keahlian')
                ->label('Keahlian Khusus (Jika Ada)')
                ->placeholder('Contoh: Stir Mobil, Las, Programming dll')
                ->visible(fn(Get $get) => Status::find($get('status_id'))?->slug == 'pencari-kerja' ?? false),
            default => null,
        };
    }
}