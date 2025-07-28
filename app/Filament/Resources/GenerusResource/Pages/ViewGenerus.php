<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class ViewGenerus extends ViewRecord
{
    protected static string $resource = GenerusResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Detail Data Muda-Mudi';
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Section::make('Sambung')
            ->schema([
                TextEntry::make('insanRole.insan.daerah.nm_daerah')
                    ->label('Daerah')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('insanRole.insan.desa.nm_desa')
                    ->label('Desa')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('insanRole.insan.kelompok.nm_kelompok')
                    ->label('Kelompok')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
            ])
            ->columns(4)
            ->columnSpan([
                'sm' => 4,
                'lg' => 3,
                'xl' => 3,
            ]),
            Section::make('Data Diri')
            ->schema([
                TextEntry::make('nis')
                    ->label('Nomor Induk'),
                TextEntry::make('insanRole.insan.nama')
                    ->label('Nama Lengkap')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('insanRole.insan.jk')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (string $state) : string => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                TextEntry::make('insanRole.insan.kota_lahir')
                    ->label('Kota Lahir')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('insanRole.insan.tgl_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y'),
                TextEntry::make('gol_dar')
                    ->label('Golongan Darah')
                    ->formatStateUsing(fn(string $state) => $state ?? '-'),
                TextEntry::make('insanRole.insan.pendidikan_terakhir')
                    ->label('Pendidikan Terakhir'),
                TextEntry::make('insanRole.insan.jurusan')
                    ->label('Jurusan/Program Studi')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('status.nm_status')
                    ->label('Status Saat Ini')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('detail_status')
                    ->label('Detail Status')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('minat.nm_minat')
                    ->label('Bidang Minat/Bakat')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('detail_minat')
                    ->label('Detail Minat/Bakat')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('siap_nikah')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'SIAP' => 'success',
                        'BELUM' => 'danger',
                    }),
            ])
            ->columns(4)
            ->columnSpan(4),
            Section::make('Orang Tua')
                    ->schema([
                        TextEntry::make('nm_ayah')
                            ->label('Nama Ayah')
                            ->formatStateUsing(fn(string $state) => $state ? Str::title($state) : '-'),
                        TextEntry::make('nm_ibu')
                            ->label('Nama Ibu')
                            ->formatStateUsing(fn(string $state) => $state ? Str::title($state) : '-'),
                        TextEntry::make('no_hp_wali')
                            ->label('Nomor HP Orang Tua'),
                        
                    ])
                    ->columns(3)
                    ->columnSpan(4)
        ])
        ->columns(4);
    }
}
