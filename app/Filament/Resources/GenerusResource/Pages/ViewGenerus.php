<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use App\Filament\Resources\GenerusResource\Pages\Views\ViewsGenerus;
use App\Models\Generus;
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
        if($this->record->kategori == 'PAUD' || $this->record->kategori == 'CABERAWIT') {
            return 'Detail Data Generus';
        } else {
            return 'Detail Data Muda-Mudi';
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit')
                ->icon('heroicon-o-pencil-square'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Section::make('Sambung')
            ->schema([
                TextEntry::make('insan.daerah.nm_daerah')
                    ->label('Daerah')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('insan.desa.nm_desa')
                    ->label('Desa')
                    ->formatStateUsing(fn(string $state) => Str::title($state)),
                TextEntry::make('insan.kelompok.nm_kelompok')
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
            ->schema(function (Generus $generus) {
                return ViewsGenerus::getColumns($generus);
            })
            ->columns(4)
            ->columnSpan(4),
            Section::make('Orang Tua')
                    ->schema([
                        TextEntry::make('insan.nm_ayah')
                            ->label('Nama Ayah')
                            ->formatStateUsing(fn(string $state) => $state ? Str::title($state) : '-'),
                        TextEntry::make('insan.nm_ibu')
                            ->label('Nama Ibu')
                            ->formatStateUsing(fn(string $state) => $state ? Str::title($state) : '-'),
                        TextEntry::make('insan.no_hp_wali')
                            ->label('Nomor HP Orang Tua'),
                        
                    ])
                    ->columns(3)
                    ->columnSpan(4)
        ])
        ->columns(4);
    }
}
