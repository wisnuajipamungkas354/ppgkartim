<?php

namespace App\Filament\Resources\GenerusResource\Pages\Views;

use App\Models\Status;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ViewsGenerus
{
    protected static array $listPaud = [
        'nis', 'nama', 'jk',
        'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'detail_status', 'kelas_ppg'
    ];

    protected static array $listCaberawit = [
        'nis', 'nama', 'jk',
        'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'detail_status',
        'kelas_di_sekolah', 'kelas_ppg',
    ];

    protected static array $listPraRemaja = [
        'nis', 'nama', 'jk',
        'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'status', 'detail_status',
        'kelas_di_sekolah', 'kelas_ppg',
    ];

    protected static array $listRemaja = [
        'nis', 'nama', 'jk',
        'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'status', 'detail_status',
        'kelas_di_sekolah', 'kelas_ppg', 'minat', 'detail_minat',
    ];

    protected static array $listPraNikah = [
        'nis', 'nama', 'jk',
        'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'pendidikan_terakhir', 'jurusan',
        'status', 'detail_status', 'kelas_ppg',
        'minat', 'detail_minat', 'siap_nikah',
    ];

    public static function getColumns(string $kategori): array
    {
        $listColumns = match (strtoupper($kategori)) {
            'PAUD' => self::$listPaud,
            'CABERAWIT' => self::$listCaberawit,
            'PRA_REMAJA' => self::$listPraRemaja,
            'REMAJA' => self::$listRemaja,
            'PRA_NIKAH' => self::$listPraNikah,
            default => [],
        };

        $listAllColumns = [
            'nis' => TextEntry::make('nis')->label('Nomor Induk'),
            'nama' => TextEntry::make('insanRole.insan.nama')->label('Nama Lengkap')->formatStateUsing(fn(?string $state) => Str::title($state)),
            'jk' => TextEntry::make('insanRole.insan.jk')->label('Jenis Kelamin')->formatStateUsing(fn(?string $state) => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
            'kota_lahir' => TextEntry::make('insanRole.insan.kota_lahir')->label('Kota Lahir')->formatStateUsing(fn(?string $state) => Str::title($state)),
            'tgl_lahir' => TextEntry::make('insanRole.insan.tgl_lahir')->label('Tanggal Lahir')->date('d/m/Y'),
            'usia' => TextEntry::make('insanRole.insan.usia')->label('Usia')->formatStateUsing(fn(?string $state) => $state . ' tahun'),
            'gol_dar' => TextEntry::make('gol_dar')->label('Golongan Darah')->formatStateUsing(fn(?string $state) => $state ?? '-'),
            'pendidikan_terakhir' => TextEntry::make('insanRole.insan.pendidikan_terakhir')->label('Pendidikan Terakhir'),
            'jurusan' => TextEntry::make('insanRole.insan.jurusan')->label('Jurusan/Program Studi')->formatStateUsing(fn(?string $state) => Str::title($state)),
            'status' => TextEntry::make('status.nm_status')->label('Status Saat Ini'),
            'detail_status' => TextEntry::make('detail_status')
                ->label(fn(Model $record) => $record->kategori != 'PRA_NIKAH' ? 'Nama Sekolah' : 'Detail Status')
                ->formatStateUsing(fn(?string $state) => Str::title($state)),
            'kelas_di_sekolah' => TextEntry::make('kelas_di_sekolah')->label('Kelas Di Sekolah')->formatStateUsing(fn(?string $state) => 'Kelas ' . $state),
            'kelas_ppg' => TextEntry::make('kelasPpg.nm_kelas')->label('Kelas Di PPG')->formatStateUsing(fn(?string $state) => Str::title($state)),
            'minat' => TextEntry::make('minat.nm_minat')->label('Bidang Minat/Bakat')->formatStateUsing(fn(?string $state) => Str::title($state)),
            'detail_minat' => TextEntry::make('detail_minat')->label('Detail Minat/Bakat')->formatStateUsing(fn(?string $state) => Str::title($state)),
            'siap_nikah' => TextEntry::make('siap_nikah')->label('Siap Nikah')->badge()->color(fn(?string $state): string => match ($state) {
                'SIAP' => 'success',
                'BELUM' => 'danger',
                default => 'gray',
            })
            ->formatStateUsing(fn(?string $state) => Str::title($state)),
        ];

        return collect($listColumns)
            ->map(fn($column) => $listAllColumns[$column] ?? null)
            ->filter()
            ->values()
            ->toArray();
    }
}
