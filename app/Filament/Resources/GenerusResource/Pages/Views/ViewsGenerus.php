<?php

namespace App\Filament\Resources\GenerusResource\Pages\Views;

use App\Models\Dapukan;
use App\Models\Generus;
use App\Models\Insan;
use App\Models\Status;
use App\Models\Minat;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ViewsGenerus
{
    /**
     * Define the fields for each generus category.
     * @var array
     */
    protected static array $categoryFields = [
        'PAUD' => [
            'nis', 'nama', 'jk', 'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'detail_status.nm_sekolah', 'kelas_ppg'
        ],
        'CABERAWIT' => [
            'nis', 'nama', 'jk', 'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'detail_status.nm_sekolah', 'detail_status.kelas_di_sekolah', 'kelas_ppg'
        ],
        'PRA_REMAJA' => [
            'nis', 'nama', 'jk', 'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'detail_status.nm_sekolah', 'detail_status.kelas_di_sekolah', 'kelas_ppg', 'minat', 'detail_minat', 'mubaligh_status'
        ],
        'REMAJA' => [
            'nis', 'nama', 'jk', 'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'no_hp', 
            'detail_status.nm_sekolah', 'detail_status.peminatan_sekolah', 'detail_status.kelas_di_sekolah', 'mubaligh_status',
            'minat', 'detail_minat', 'status', 'kelas_ppg'
        ],
        'PRA_NIKAH' => [
            'nis', 'nama', 'jk', 'kota_lahir', 'tgl_lahir', 'usia', 'gol_dar', 'no_hp', 'pendidikan_terakhir', 'jurusan',
            'mubaligh_status', 'status', 'detail_status_pra_nikah',
            'kelas_ppg', 'minat', 'detail_minat', 'siap_nikah'
        ],
    ];

    public static function getColumns($generus): array
    {
        $listColumns = self::$categoryFields[strtoupper($generus->kategori)] ?? [];

        $isMubaligh = Insan::where('id', $generus->insan_id)->where('is_mubaligh', true)->first() ? 'MS' : 'BUKAN';
        
        $listAllColumns = [
            'nis' => TextEntry::make('nis')->label('Nomor Induk'),
            'nama' => TextEntry::make('insan.nama')
                ->label('Nama Lengkap')
                ->formatStateUsing(fn (?string $state) => Str::title($state)),
            'jk' => TextEntry::make('insan.jk')
                ->label('Jenis Kelamin')
                ->formatStateUsing(fn (?string $state) => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
            'kota_lahir' => TextEntry::make('insan.kota_lahir')
                ->label('Kota Lahir')
                ->formatStateUsing(fn (?string $state) => Str::title($state)),
            'tgl_lahir' => TextEntry::make('insan.tgl_lahir')
                ->label('Tanggal Lahir')
                ->date('d/m/Y'),
            'usia' => TextEntry::make('insan.usia')
                ->label('Usia')
                ->formatStateUsing(fn (?string $state) => $state ? $state . ' tahun' : '-'),
            'gol_dar' => TextEntry::make('gol_dar')
                ->label('Golongan Darah')
                ->formatStateUsing(fn (?string $state) => $state ?? '-'),
            'pendidikan_terakhir' => TextEntry::make('insan.pendidikan_terakhir')
                ->label('Pendidikan Terakhir')
                ->formatStateUsing(fn (?string $state) => Status::where('slug', $state)->value('nm_status')),
            'jurusan' => TextEntry::make('insan.jurusan')
                ->label('Jurusan/Program Studi')
                ->formatStateUsing(fn (?string $state) => $state ? Str::title($state) : '-'),
            'status' => TextEntry::make('status.nm_status')
                ->label('Status Saat Ini')
                ->formatStateUsing(fn (?string $state) => $state ?? '-')
                ->hidden(fn() => $isMubaligh == 'MUBALIGH TUGASAN'),
            
            // Kolom untuk detail_status yang spesifik
            'detail_status.nm_sekolah' => TextEntry::make('detail_status.nm_sekolah')
                ->label('Nama Sekolah')
                ->formatStateUsing(fn (?string $state) => $state ? Str::upper($state) : '-'),
            'detail_status.peminatan_sekolah' => TextEntry::make('detail_status.peminatan_sekolah')
                ->label('Peminatan/Jurusan')
                ->formatStateUsing(fn (?string $state) => $state ? Str::title($state) : '-'),
            'detail_status.kelas_di_sekolah' => TextEntry::make('detail_status.kelas_di_sekolah')
                ->label('Kelas Di Sekolah')
                ->formatStateUsing(fn (?string $state) => $state ? 'Kelas ' . $state : '-'),

            // Kolom untuk Pra Nikah dan detail_status array
            'detail_status_pra_nikah' => TextEntry::make('detail_status')
                ->label('Detail Status')
                ->formatStateUsing(function (Model $record) {
                    $statusId = $record->status_id;
                    $statusSlug = Status::find($statusId)->slug ?? null;
                    $detailStatus = $record->detail_status;
                    // dd($detailStatus);
                    if (in_array($statusSlug, ['d3', 's1-d4', 's2', 's3', 'kuliah-kerja']) && isset($detailStatus['program_studi']) && isset($detailStatus['universitas'])) {
                        return Str::title("{$detailStatus['program_studi']} di {$detailStatus['universitas']}");
                    } elseif (in_array($statusSlug, ['karyawan-pegawai', 'kuliah-kerja']) && isset($detailStatus['jabatan']) && isset($detailStatus['nm_perusahaan'])) {
                        return "{$detailStatus['jabatan']}/{$detailStatus['nm_perusahaan']}";
                    } elseif ($statusSlug === 'wirausaha' && isset($detailStatus['nm_usaha'])) {
                        return Str::title("Wirausaha ({$detailStatus['bidang_usaha']}) - {$detailStatus['nm_usaha']}");
                    } elseif ($statusSlug === 'pencari-kerja' && isset($detailStatus['keahlian'])) {
                        return Str::title("Pencari Kerja dengan keahlian: {$detailStatus['keahlian']}");
                    }

                    return '-';
                })
                ->hidden(fn() => $isMubaligh == 'MUBALIGH TUGASAN'),

            // Kolom untuk Mubaligh
            'mubaligh_status' => TextEntry::make('insan.id')
                ->label('Mubaligh')
                ->badge()
                ->formatStateUsing(fn (string $state) => $state = $isMubaligh)
                ->color(function (?string $state) use ($isMubaligh) {
                    $state = $isMubaligh;
                    return match ($state) {
                    'MS' => 'info',
                    'BUKAN' => 'warning',
                    default => 'gray'
                };}),
            
            'mubaligh_data' => TextEntry::make('dapukan')
                ->label('Data Mubaligh')
                ->formatStateUsing(function (Model $record) {
                    $insan = $record->insan;
                    if ($insan->dapukan->nm_dapukan === 'MUBALIGH SETEMPAT') {
                        $ms = $insan->mubalighSetempat;
                        return $ms ? "Asal Pondok: {$ms->asal_pondok}, Total Tugas: {$ms->jml_tugas} kali, Lama Tugas: {$ms->lama_tugas}" : '-';
                    }
                    return '-';
                }),

            // Kolom-kolom lainnya
            'no_hp' => TextEntry::make('insan.no_hp')
                ->label('No HP/WhatsApp')
                ->formatStateUsing(fn (?string $state) => $state ?? '-'),
            'kelas_ppg' => TextEntry::make('kelasPpg')
                ->label('Kelas Di PPG')
                ->formatStateUsing(fn (Model $record) => $record->kelasPpg->nm_kelas ? $record->kelasPpg->nm_kelas : '(-)')
                ->hidden(fn() => $isMubaligh == 'MUBALIGH TUGASAN'),
            'minat' => TextEntry::make('insan.minat_bakat')
                ->label('Bidang Minat/Bakat')
                ->formatStateUsing(function (?string $state) {
                    $result = '';
                    $state = explode(', ', $state);
                    if($state) {
                        foreach($state as $index => $slug) {
                            $result = $index == 0 ? Minat::query()->where('slug', $slug)->value('nm_minat') : $result . ', ' . Minat::query()->where('slug', $slug)->value('nm_minat');
                        }
                    }
                    return $result;
                }),
            'siap_nikah' => TextEntry::make('insan.siap_nikah')
                ->label('Siap Nikah')
                ->badge()
                ->color(fn (?string $state): string => match ($state) {
                    'SIAP' => 'success',
                    'BELUM' => 'danger',
                    default => 'gray',
                })
        ];

        return collect($listColumns)
            ->map(fn($column) => $listAllColumns[$column] ?? null)
            ->filter()
            ->values()
            ->toArray();
    }
}