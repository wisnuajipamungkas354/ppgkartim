<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenerusResource\Pages;
use App\Filament\Resources\GenerusResource\RelationManagers;
use App\Helpers\AccessHelper;
use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Kelompok;
use App\Models\Generus;
use App\Models\Insan;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class GenerusResource extends Resource
{
    protected static ?string $model = Generus::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Generus';

    protected static ?string $navigationGroup = 'Database';

    public static function canViewAny(): bool
    {
        if(!auth()->user()->hasRole('super_admin')) {
            return AccessHelper::canAccess(static::getSlug());
        } else {
            return true;
        }
    }

    public static function form(Form $form): Form
    {   
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Jenis Data')
                        ->schema([
                            Forms\Components\Select::make('kategori')
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
                    Wizard\Step::make('Sambung')
                        ->schema([
                            Forms\Components\Select::make('daerah_id')
                                ->label('Daerah')
                                ->options(function() {
                                    if(auth()->user()->hasRole('super_admin')) {
                                        return Daerah::query()->pluck('nm_daerah', 'id');
                                    }
                                })
                                ->required()
                                ->live()
                                ->preload()
                                ->visible(auth()->user()->hasRole('super_admin')),
                            Forms\Components\Select::make('desa_id')
                                ->label('Desa')
                                ->options(function(Get $get){
                                    $roleName = AccessHelper::getActiveRoleName();
                                    if(in_array($roleName, ['super_admin', 'mudamudi_daerah', 'phppg', 'kurikulum'])) {
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
                                ->visible(fn() => in_array(AccessHelper::getActiveRoleName(), ['super_admin',  'mudamudi_daerah', 'phppg', 'kurikulum'])),
                            Forms\Components\Select::make('kelompok_id')
                                ->label('Kelompok')
                                ->options(function (Get $get) {
                                    $roleName = AccessHelper::getActiveRoleName();
                                    if(in_array($roleName, ['super_admin', 'phppg', 'kurikulum', 'mudamudi_daerah', 'pjp_desa', 'mudamudi_desa'])) {
                                        return Kelompok::query()->where('desa_id', $get('desa_id'))->pluck('nm_kelompok', 'id');
                                    }
                                })
                                ->required()
                                ->searchable()
                                ->live()
                                ->preload()
                                ->visible(fn() => in_array(AccessHelper::getActiveRoleName(), ['super_admin', 'phppg', 'kurikulum', 'mudamudi_daerah', 'pjp_desa', 'mudamudi_desa'])),
                    ])->visible(fn() => in_array(AccessHelper::getActiveRoleName(), ['super_admin', 'phppg', 'kurikulum', 'mudamudi_daerah', 'pjp_desa', 'mudamudi_desa'])),
                    Wizard\Step::make('Data Diri')
                        ->schema([
                            Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->placeholder('Masukkan Nama Lengkap')
                            // Mengubah Text Menjadi Camel Casing
                            ->dehydrateStateUsing(fn ($state) => Str::title($state))
                            ->maxLength(255)
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Radio::make('jk')
                            ->label('Jenis Kelamin')
                            ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                            ->required(),
                        Forms\Components\TextInput::make('kota_lahir')
                            ->label('Kota Lahir')
                            ->placeholder('Kota Lahir')
                            // Mengubah Text Menjadi Camel Casing
                            ->dehydrateStateUsing(fn ($state) => Str::title($state))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tgl_lahir')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->label('Tanggal Lahir')
                            ->maxDate(now()->format('Y-m-d'))
                            ->required(),
                        Forms\Components\Radio::make('mubaligh')
                            ->label('Mubaligh')
                            ->options(['MT' => 'Mubaligh Tugas (MT)', 'MS' => 'Mubaligh Setempat (MS)', 'BUKAN' => 'Bukan Mubaligh'])
                            ->required(),
                        Forms\Components\Select::make('status_id')
                            ->label('Status')
                            ->relationship(
                                name: 'status', 
                                titleAttribute: 'nm_status', 
                                modifyQueryUsing: function(Builder $query, Get $get) {
                                    if(in_array($get('kategori'), ['PAUD', 'CABERAWIT'])) {
                                        $query->where('slug', 'paudtk')->orWhere('slug', 'sd');
                                    } else {
                                        
                                    }
                                })
                            ->live()
                            ->required(),
                        Forms\Components\Textarea::make('detail_status')
                            ->label('Detail Status')
                            ->placeholder(fn (Get $get) => Status::query()->where('id', $get('status_id'))->value('placeholder'))
                            ->required(),
                        Forms\Components\Select::make('kelas_di_sekolah')
                            ->label('Kelas Di Sekolah')
                            ->options(function(Get $get) {
                                if($get('status_id') !== null) {

                                    $status = Status::find($get('status_id'));
                                    switch($status->nm_status) {
                                        case 'SD' :
                                            return [
                                                1 => 'Kelas 1',
                                                2 => 'Kelas 2',
                                                3 => 'Kelas 3',
                                                4 => 'Kelas 4',
                                                5 => 'Kelas 5',
                                                6 => 'Kelas 6',
                                            ];
                                        case 'SMP' :
                                            return [
                                                7 => 'Kelas 7',
                                                8 => 'Kelas 8',
                                                9 => 'Kelas 9',
                                            ];
                                        case 'SMA/K' :
                                            return [
                                                10 => 'Kelas 10',
                                                11 => 'Kelas 11',
                                                12 => 'Kelas 12',
                                            ];
                                        default: 
                                            return [];
                                    }
                                }
                            })
                            ->preload()
                            ->visible(function(Get $get) {
                                if($get('status_id') !== null) {
                                    $status = Status::find($get('status_id'));
                                    switch ($status->nm_status) {
                                        case 'SD' :
                                            return true;
                                        case 'SMP' :
                                            return true;
                                        case 'SMA/K' :
                                            return true;
                                        default:
                                            return false;
                                    }
                                }
                            }),
                        Forms\Components\Radio::make('siap_nikah')
                            ->label('Siap Nikah')
                            ->options(['Siap' => 'Siap', 'Belum' => 'Belum'])
                            ->required()
                            ->visible(function(Get $get) {
                                if($get('status_id') != null) {
                                    $status = Status::find($get('status_id'))->value('slug');
                                    if(!in_array($status, ['paudtk', 'sd', 'smp', 'sma-smk'])) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }
                            }),
                        ]),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('insanrole.insan.desa.nm_desa')
                    ->label('Desa')
                    ->formatStateUsing(fn (string $state) => Str::title($state)),
                TextColumn::make('insanrole.insan.kelompok.nm_kelompok')
                    ->label('Kelompok')
                    ->formatStateUsing(fn (string $state) => Str::title($state)),
                TextColumn::make('insanrole.insan.nama')
                    ->label('Nama Lengkap')
                    ->searchable(),
                TextColumn::make('insanrole.insan.jk')
                    ->label('L/P'),
                TextColumn::make('insanrole.insan.kota_lahir')
                    ->label('Kota Lahir')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('insanrole.insan.tgl_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status.nm_status')
                    ->label('Status'),
                TextColumn::make('detail_status')
                    ->label('Detail Status'),
                TextColumn::make('insanrole.insan.usia')
                    ->label('Usia'),
                TextColumn::make('insanrole.insan.no_hp')
                    ->label('No HP'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeneruses::route('/'),
            'create' => Pages\CreateGenerus::route('/create'),
            'edit' => Pages\EditGenerus::route('/{record}/edit'),
        ];
    }
}
