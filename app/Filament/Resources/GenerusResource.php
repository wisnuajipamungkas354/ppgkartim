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
use App\Filament\Resources\GenerusResource\Pages\Forms\GenerusForm;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                                    if(AccessHelper::isSuperAdmin()) {
                                        return Daerah::query()->pluck('nm_daerah', 'id');
                                    }
                                })
                                ->required()
                                ->live()
                                ->preload()
                                ->visible(fn() => AccessHelper::isSuperAdmin()),
                            Forms\Components\Select::make('desa_id')
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
                            Forms\Components\Select::make('kelompok_id')
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
                    Wizard\Step::make('Data Diri')
                        ->schema(function(Get $get): array {
                           switch($get('kategori')) {
                            case 'PAUD':
                                return GenerusForm::getPaudForm($get);
                            case 'CABERAWIT':
                                return GenerusForm::getCaberawitForm($get);
                            case 'PRA_REMAJA': 
                                return GenerusForm::getPraRemajaForm($get);
                            case 'REMAJA': 
                                return GenerusForm::getRemajaForm($get);
                            default:
                                return GenerusForm::getPraNikahForm($get);
                           }
                        }),
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
                            Forms\Components\Select::make('minat_id')
                                ->label('Kategori Minat Bakat')
                                ->relationship('minat', 'nm_minat'),
                            Forms\Components\TextInput::make('detail_minat')
                                ->label('Sebutkan nama minat bakat'),
                    ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nis')
                    ->label('NIS'),
                TextColumn::make('insanrole.insan.desa.nm_desa')
                    ->label('Desa')
                    ->formatStateUsing(fn (string $state) => Str::title($state))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('insanrole.insan.kelompok.nm_kelompok')
                    ->label('Kelompok')
                    ->formatStateUsing(fn (string $state) => Str::title($state))
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus Data')
                    ->modalDescription('Sebelum dihapus, Mohon Amal Sholih mengisi keterangan dihapus dibawah ini')
                    ->form([
                        Forms\Components\Select::make('keterangan')
                            ->label('Keterangan Dihapus')
                            ->options([
                                'Menikah' => 'Menikah',
                                'Mondok' => 'Mondok',
                                'Meninggal' => 'Meninggal',
                                'Pindah Sambung Dalam Daerah' => 'Pindah Sambung Dalam Daerah',
                                'Pindah Sambung Keluar Daerah' => 'Pindah Sambung Keluar Daerah',
                                'Data Duplikat' => 'Data Duplikat',
                            ])
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('desa_id')
                            ->label('Nama Desa')
                            ->options(function (Generus $record) {
                                return Desa::query()->where('daerah_id', $record->insanRole->insan->daerah_id)->pluck('nm_desa', 'id');
                            })
                            ->preload()
                            ->live()
                            ->required()
                            ->visible(fn (Get $get): bool => $get('keterangan') == 'Pindah Sambung Dalam Daerah' ? true : false),
                        Forms\Components\Select::make('kelompok_id')
                            ->label('Nama Kelompok')
                            ->options(function (Get $get) {
                                return Kelompok::query()->where('desa_id', $get('desa_id'))->pluck('nm_kelompok', 'id');
                            })
                            ->preload()
                            ->live()
                            ->required()
                            ->visible(fn (Get $get): bool => $get('keterangan') == 'Pindah Sambung Dalam Daerah' ? true : false)
                    ])
                    ->modalSubmitActionLabel('Hapus Data')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (array $data, Generus $record) {
                        $record->riwayat_delete = $data['keterangan'];

                        $record->delete();

                        Notification::make()
                            ->success()
                            ->title('Berhasil Dihapus')
                            ->send();
                    }),
                    Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tidak ada data');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeneruses::route('/'),
            'create' => Pages\CreateGenerus::route('/create'),
            'view' => Pages\ViewGenerus::route('/{record}'),
            'edit' => Pages\EditGenerus::route('/{record}/edit'),
        ];
    }
}
