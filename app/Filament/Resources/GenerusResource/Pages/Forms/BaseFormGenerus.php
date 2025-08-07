<?php

namespace App\Filament\Resources\GenerusResource\Pages\Forms;

use App\Helpers\AccessHelper;
use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Kelompok;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;

class BaseFormGenerus
{
  public static function getBaseForm(): array
    {
        return [
            Step::make('Jenis Data')
                ->schema([
                    Select::make('kategori')
                        ->label('Kategori Generus')
                        ->options(function() {
                            $roleName = AccessHelper::getActiveRoleName();
                            if(in_array($roleName, ['phppg', 'pjp_desa', 'pjp_kelompok', 'kurikulum', 'super_admin'])) {
                                return [
                                    'PAUD' => 'Paud/TK',
                                    'CABERAWIT' => 'Caberawit (SD)',
                                    'PRA_REMAJA' => 'Pra Remaja (SMP)',
                                    'REMAJA' => 'Remaja (SMA/K)',
                                    'PRA_NIKAH' => 'Pra Nikah (Lepas Pelajar)'
                                ];
                            } elseif(in_array($roleName, ['mudamudi_daerah', 'mudamudi_desa', 'mudamudi_kelompok'])) {
                                return [
                                    'PRA_REMAJA' => 'Pra Remaja (SMP)',
                                    'REMAJA' => 'Remaja (SMA/K)',
                                    'PRA_NIKAH' => 'Pra Nikah (Lepas Pelajar)'
                                ];
                            }
                        })
                        ->required()
                ]),
            Step::make('Data Diri')
                ->schema(fn(Get $get) => GenerusForm::getForms($get)),
                Step::make('Sambung')
                ->schema([
                    Select::make('daerah_id')
                        ->label('Daerah')
                        ->options(function() {
                            if(AccessHelper::isSuperAdmin()) {
                                return Daerah::query()->pluck('nm_daerah', 'id');
                            }
                        })
                        ->required()
                        ->live()
                        ->preload()
                        ->visible(fn() => AccessHelper::isSuperAdmin()),
                    Select::make('desa_id')
                        ->label('Desa')
                        ->options(function(Get $get){
                            if(AccessHelper::isDaerah() || AccessHelper::isSuperAdmin()) {
                                if(auth()->user()->hasRole(['super_admin'])) {
                                    return Desa::query()->where('daerah_id', $get('daerah_id'))->pluck('nm_desa', 'id');
                                } else {
                                    return Desa::query()->where('daerah_id', auth()->user()->daerah_id)->pluck('nm_desa', 'id');
                                }
                            }
                        })
                        ->required()
                        ->searchable()
                        ->afterStateUpdated(fn (Set $set) => $set('kelompok_id', null))
                        ->live()
                        ->preload()
                        ->visible(fn() => AccessHelper::isDaerah() || AccessHelper::isSuperAdmin()),
                    Select::make('kelompok_id')
                        ->label('Kelompok')
                        ->options(function (Get $get) {
                            if(!AccessHelper::isKelompok()) {
                                return Kelompok::query()->where('desa_id', $get('desa_id'))->pluck('nm_kelompok', 'id');
                            }
                        })
                        ->required()
                        ->searchable()
                        ->live()
                        ->preload()
                        ->visible(fn() => !AccessHelper::isKelompok()),
            ])->visible(fn() => !AccessHelper::isKelompok()),
            Step::make('Orang Tua')
                ->schema([
                    TextInput::make('nm_ayah')
                        ->label('Nama Ayah Kandung')
                        ->placeholder('Masukkan nama ayah'),
                    TextInput::make('nm_ibu')
                        ->label('Nama Ibu Kandung')
                        ->placeholder('Masukkan nama ibu'),
                    TextInput::make('no_hp_wali')
                        ->label('Nomor HP/WhatsApp Orang Tua')
                        ->placeholder('Masukkan nomor HP/WA'),
                ]),
            Step::make('Minat & Bakat')
                ->schema([
                    Select::make('minat_id')
                        ->label('Kategori Minat Bakat')
                        ->relationship('minat', 'nm_minat')
                        ->required(fn(Get $get) => $get('kategori') == 'PRA_NIKAH'),
                    TextInput::make('detail_minat')
                        ->label('Sebutkan nama minat bakat, Contoh: Sepak Bola')
                        ->required(fn(Get $get) => $get('kategori') == 'PRA_NIKAH'),
            ])
        ];
    }
}