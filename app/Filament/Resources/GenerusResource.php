<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenerusResource\Pages;
use App\Filament\Resources\GenerusResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Helpers\AccessHelper;
use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Kelompok;
use App\Models\Generus;
use App\Models\Status;
use App\Models\Insan;
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
use App\Traits\HandlesActiveRolePermission;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GenerusResource extends Resource implements HasShieldPermissions
{
    use HandlesActiveRolePermission;

    protected static ?string $model = Generus::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Generus';

    protected static ?string $navigationGroup = 'Database';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'approve'
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nis')
                    ->label('NIS'),
                TextColumn::make('insan.desa.nm_desa')
                    ->label('Desa')
                    ->formatStateUsing(fn (string $state) => Str::title($state))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('insan.kelompok.nm_kelompok')
                    ->label('Kelompok')
                    ->formatStateUsing(fn (string $state) => Str::title($state))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('insan.nama')
                    ->label('Nama Lengkap')
                    ->formatStateUsing(fn (string $state) => Str::title($state))
                    ->searchable(),
                TextColumn::make('insan.jk')
                    ->label('L/P')
                    ->sortable(),
                TextColumn::make('insan.kota_lahir')
                    ->label('Kota Lahir')
                    ->formatStateUsing(fn(string $state) => Str::title($state))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('insan.tgl_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status.nm_status')
                    ->label('Status'),
                TextColumn::make('detail_status')
                    ->label('Detail Status')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('insan.usia')
                    ->label('Usia')
                    ->sortable(),
                TextColumn::make('insan.no_hp')
                    ->label('No HP')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                                return Desa::query()->where('daerah_id', $record->insan->daerah_id)->pluck('nm_desa', 'id');
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
                        
                        if($data['keterangan'] == 'Data Duplikat') {
                            Insan::destroy($record->insan->id);
                        } else {
                            $record->delete();
                        }

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
            ->where('is_verified', true)
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
