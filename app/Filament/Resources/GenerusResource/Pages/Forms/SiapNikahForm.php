<?php

namespace App\Filament\Resources\GenerusResource\Pages\Forms;

use Filament\Forms\Get;
use Filament\Forms;

class SiapNikahForm
{
  protected static function getSiapNikahForm(string $field, array $options, Get $get): ?Forms\Components\Component
  {
    return match ($field) {
        'tinggi_badan' => Forms\Components\TextInput::make('tinggi_badan')
            ->label('Tinggi Badan')
            ->numeric()
            ->placeholder('Masukkan tinggi badanmu (Hanya dapat dilihat oleh Tim PNKB)')
            ->suffix('cm')
            ->visible(fn(Get $get) => $get('siap_nikah') == 'SIAP'),
        'berat_badan' => Forms\Components\TextInput::make('berat_badan')
            ->label('Berat Badan')
            ->placeholder('Masukkan berat badanmu (Hanya dapat dilihat oleh Tim PNKB)')
            ->suffix('kg')
            ->visible(fn(Get $get) => $get('siap_nikah') == 'SIAP'),
        'kriteria_pasangan' => Forms\Components\TextArea::make('kriteria_pasangan')
            ->label('Tuliskan Kriteria Pasangan yang Kamu Inginkan!')
            ->placeholder('Datamu bersifat rahasia, hanya dapat dilihat oleh Tim PNKB')
            ->required()
            ->visible(fn(Get $get) => $get('siap_nikah') == 'SIAP'),
        default => null
    };
  }
}