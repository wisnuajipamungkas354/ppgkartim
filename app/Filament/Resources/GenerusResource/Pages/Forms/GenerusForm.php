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
  public $statusId;

    protected static function getCommonFields(): array
    {
        return [
            Forms\Components\TextInput::make('nama')
                ->label('Nama Lengkap')
                ->placeholder('Masukkan Nama Lengkap')
                ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                ->maxLength(255)
                ->required()
                ->columnSpanFull(),

            Forms\Components\Radio::make('jk')
                ->label('Jenis Kelamin')
                ->options([
                    'L' => 'Laki-laki',
                    'P' => 'Perempuan',
                ])
                ->required(),

            Forms\Components\TextInput::make('kota_lahir')
                ->label('Kota Lahir')
                ->placeholder('Kota Lahir')
                ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                ->required()
                ->maxLength(255),

            Forms\Components\DatePicker::make('tgl_lahir')
                ->displayFormat('d/m/Y')
                ->label('Tanggal Lahir')
                ->maxDate(now()->format('Y-m-d'))
                ->required(),

            Forms\Components\Select::make('gol_dar')
                ->label('Golongan Darah')
                ->options([
                    'A' => 'A',
                    'B' => 'B',
                    'O' => 'O',
                    'AB' => 'AB',
                ]),
        ];
    }

    public static function getPaudForm(Get $get): array
    {
        return array_merge(
            self::getCommonFields(),
            [
                Forms\Components\Textarea::make('detail_status')
                    ->label('Nama Paud/TK')
                    ->placeholder(fn () => Status::where('slug', 'paudtk')->value('placeholder'))
                    ->required(),
            ]
        );
    }

    public static function getCaberawitForm(Get $get): array
    {
        return array_merge(
            self::getCommonFields(),
            [
                Forms\Components\Textarea::make('detail_status')
                    ->label('Nama Sekolah Dasar')
                    ->placeholder(fn () => Status::where('slug', 'sd')->value('placeholder'))
                    ->required(),
                Forms\Components\Select::make('kelas_di_sekolah')
                    ->label('Kelas di sekolah')
                    ->options([
                        1 => 'Kelas 1',
                        2 => 'Kelas 2',
                        3 => 'Kelas 3',
                        4 => 'Kelas 4',
                        5 => 'Kelas 5',
                        6 => 'Kelas 6',
                    ])
                    ->required(),
                Forms\Components\Select::make('kelas_ppg_id')
                    ->label('Kelas di PPG')
                    ->options(fn() => KelasPpg::all()->pluck('nm_kelas', 'id')),
                ]
        );
    }

    public static function getPraRemajaForm(Get $get): array
    {
        return array_merge(
            self::getCommonFields(),
            [
                Forms\Components\Textarea::make('detail_status')
                    ->label('Nama SMP / Mts')
                    ->placeholder(fn () => Status::where('slug', 'smp')->value('placeholder'))
                    ->required(),
                Forms\Components\Select::make('kelas_di_sekolah')
                    ->label('Kelas di sekolah')
                    ->options([
                        7 => 'Kelas 7',
                        8 => 'Kelas 8',
                        9 => 'Kelas 9',
                    ])
                    ->required(),
                Forms\Components\Select::make('kelas_ppg_id')
                    ->label('Kelas di PPG')
                    ->options(fn() => KelasPpg::all()->pluck('nm_kelas', 'id')),
            ]
        );
    }

    public static function getRemajaForm(Get $get): array
    {
        return array_merge(
            self::getCommonFields(),
            [
                Forms\Components\Textarea::make('detail_status')
                    ->label('Nama SMA / SMK / MA')
                    ->placeholder(fn () => Status::where('slug', 'sma-smk')->value('placeholder'))
                    ->required(),
                Forms\Components\Select::make('kelas_di_sekolah')
                    ->label('Kelas di sekolah')
                    ->options([
                        10 => 'Kelas 10',
                        11 => 'Kelas 11',
                        12 => 'Kelas 12',
                    ])
                    ->required(),
                Forms\Components\Select::make('kelas_ppg_id')
                    ->label('Kelas di PPG')
                    ->options(fn() => KelasPpg::all()->pluck('nm_kelas', 'id')),
                ]
        );
    }

    public static function getPraNikahForm(Get $get): array
    {
        return array_merge(
            self::getCommonFields(),
            [
                Forms\Components\Select::make('pendidikan_terakhir')
                    ->label('Pendidikan Terakhir')
                    ->options(
                        fn() => Status::query()->whereIn('slug', ['paudtk','sd','smp','sma-smk','d3','s1-d4','s2','s3'])->pluck('nm_status', 'nm_status')->toArray()
                        )
                    ->required(),
                Forms\Components\Textarea::make('jurusan')
                    ->label('Jurusan/Program Studi Pendidikan Terakhir')
                    ->placeholder('Masukkan Jurusan/Program Studi Pendidikan Terakhir, Contoh: Manajemen'),
                Forms\Components\Select::make('status_id')
                    ->label('Status Saat Ini')
                    ->options(
                        fn() => Status::whereNotIn('slug', ['paudtk','sd','smp','sma-smk'])->pluck('nm_status', 'id'),
                    )
                    ->live()
                    ->required(),
                Forms\Components\Textarea::make('detail_status')
                    ->label('Detail Status')
                    ->placeholder(fn(Get $get) => Status::query()->where('id', $get('status_id'))->value('placeholder'))
                    ->required(),
                Forms\Components\Select::make('kelas_ppg_id')
                    ->label('Kelas di PPG')
                    ->options(fn() => KelasPpg::all()->pluck('nm_kelas', 'id')),
                Forms\Components\TextInput::make('no_hp')
                    ->label('No HP/WhatsApp')
                    ->placeholder('Masukkan nomor HP/WA'),
                Forms\Components\Radio::make('siap_nikah')
                    ->label('Siap Nikah')
                    ->options([
                        'BELUM' => 'Belum Siap',
                        'SIAP' => 'Siap Nikah'
                    ])
                    ->descriptions([
                        'BELUM' => 'Belum siap menikah',
                        'SIAP' => 'Siap Nikah berarti siap untuk dilancarkan, dicarikan, dan ditaaruf/dikenalkan oleh Pengurus PNKB'
                    ])
                    ->required(),
            ]
        );
    }
}