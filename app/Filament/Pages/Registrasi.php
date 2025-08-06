<?php

namespace App\Filament\Pages;

use App\Filament\Resources\GenerusResource\Pages\Forms\GenerusForm;
use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\Insan;
use App\Models\Kelompok;
use App\Traits\HandlesPermissionPage;
use Carbon\Carbon;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ForceDeleteAction;

class Registrasi extends Page implements HasTable
{
    use HandlesPermissionPage, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.registrasi';

    protected static ?string $navigationLabel = 'Registrasi Data';

    protected static ?string $navigationGroup = 'Database';

    protected static ?string $title = 'Registrasi';

    public function table(Table $table): Table
    {
        return $table
            ->query(Generus::query()->where('is_verified', false))
            ->columns([
                TextColumn::make('insanrole.insan.desa.nm_desa')
                    ->label('Desa')
                    ->formatStateUsing(fn (string $state) => Str::title($state)),
                TextColumn::make('insanrole.insan.kelompok.nm_kelompok')
                    ->label('Kelompok')
                    ->formatStateUsing(fn (string $state) => Str::title($state)),
                TextColumn::make('insanrole.insan.nama')
                    ->label('Nama Lengkap')
                    ->formatStateUsing(fn (string $state) => Str::title($state))
                    ->searchable(),
                TextColumn::make('insanrole.insan.jk')
                    ->label('L/P')
                    ->sortable(),
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
                    ->label('Detail Status')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-m-check')
                    ->form([
                        Fieldset::make('Jenis Data')
                            ->schema([
                                Select::make('kategori')
                                    ->label('Kategori Generus')
                                    ->options([
                                                'PAUD' => 'Paud/TK',
                                                'CABERAWIT' => 'Caberawit (SD)',
                                                'PRA_REMAJA' => 'Pra Remaja (SMP)',
                                                'REMAJA' => 'Remaja (SMA/K)',
                                                'PRA_NIKAH' => 'Pra Nikah (Lepas Pelajar)'
                                            ])
                                    ->disabled()
                                    ]),
                        Fieldset::make('Sambung')
                            ->schema([
                                Select::make('daerah_id')
                                    ->label('Daerah')
                                    ->options(fn() => Daerah::query()->pluck('nm_daerah', 'id'))
                                    ->afterStateUpdated(fn (Set $set) => $set('desa_id', null))
                                    ->required()
                                    ->live()
                                    ->preload(),
                                Select::make('desa_id')
                                    ->label('Desa')
                                    ->options(fn(Get $get) => Desa::query()->where('daerah_id', $get('daerah_id'))->pluck('nm_desa', 'id'))
                                    ->required()
                                    ->live()
                                    ->preload(),
                                Select::make('kelompok_id')
                                    ->label('Kelompok')
                                    ->options(fn(Get $get) => Kelompok::where('desa_id', $get('desa_id'))->pluck('nm_kelompok', 'id'))
                                    ->required()
                                    ->live()
                                    ->preload(),
                            ])
                            ->columns(3),
                        FieldSet::make('Data Diri')
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
                        FieldSet::make('Orang Tua')
                             ->schema([
                                TextInput::make('nm_ayah')
                                    ->label('Nama Ayah')
                                    ->placeholder('Masukkan nama ayah'),
                                TextInput::make('nm_ibu')
                                    ->label('Nama Ibu')
                                    ->placeholder('Masukkan nama ibu'),
                                TextInput::make('no_hp_wali')
                                    ->label('Nomor HP/WhatsApp Orang Tua')
                                    ->placeholder('Masukkan nomor HP/WA'),
                             ]),
                        FieldSet::make('Minat & Bakat')
                             ->schema([
                                Select::make('minat_id')
                                    ->label('Kategori Minat Bakat')
                                    ->relationship('minat', 'nm_minat'),
                                TextInput::make('detail_minat')
                                    ->label('Sebutkan nama minat bakat'),
                             ])
                    ])
                    ->fillForm(function (Generus $record): array {
                        return [
                            'daerah_id' => $record->insanRole->insan->daerah_id,
                            'desa_id' => $record->insanRole->insan->desa_id,
                            'kelompok_id' => $record->insanRole->insan->kelompok_id,
                            'nama' => $record->insanRole->insan->nama,
                            'jk' => $record->insanRole->insan->jk,
                            'kota_lahir' => $record->insanRole->insan->kota_lahir,
                            'tgl_lahir' => $record->insanRole->insan->tgl_lahir,
                            'no_hp' => $record->insanRole->insan->no_hp,
                            'pendidikan_terakhir' => $record->insanRole->insan->pendidikan_terakhir,
                            'jurusan' => $record->insanRole->insan->jurusan,
                            'insan_role_id' => $record->insanRole->id,
                            'nis' => $record->nis,
                            'jenis_data' => $record->jenis_data,
                            'kategori' => $record->kategori,
                            'gol_dar' => $record->gol_dar,
                            'kelas_ppg_id' => $record->kelas_ppg_id,
                            'status_id' => $record->status_id,
                            'detail_status' => $record->detail_status,
                            'kelas_di_sekolah' => $record->kelas_di_sekolah,
                            'nm_ayah' => $record->nm_ayah,
                            'nm_ibu' => $record->nm_ibu,
                            'no_hp_wali' => $record->no_hp_wali,
                            'minat_id' => $record->minat_id,
                            'detail_minat' => $record->detail_minat,
                            'siap_nikah' => $record->siap_nikah,
                            'is_verified' => $record->is_verified,
                            'riwayat_update' => $record->riwayat_update,
                        ];
                    })
                    ->action(function(array $data, Generus $record) {
                        // Update Data
                        $data['usia'] = Carbon::parse($data['tgl_lahir'])->age ?? null;
                        $data['riwayat_update'] = 'APPROVED';
                        $data['is_verified'] = true;
                        
                        $insan = Insan::find($record->insanRole->insan->id);
                        
                        $insan->update($data);
                        $record->update($data);

                        Notification::make('success_notification')
                            ->title('Berhasil di Approve')
                            ->success()
                            ->send();
                    })
                    ->modalSubmitActionLabel('Approve')
                    ->modalCancelActionLabel('Kembali')
                    ->slideOver()
                    ->visible(fn() => auth()->user()->hasPermissionTo('approve_generus')),
                ForceDeleteAction::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-trash')
                    ->before(function (Generus $record) {
                        $insan = Insan::find($record->insanRole->insan->id);
                        $insan->forceDelete();
                    })
                    ->successNotification(
                        Notification::make()
                        ->success()
                        ->title('Berhasil di reject')
                    )
                    ->visible(fn() => auth()->user()->hasPermissionTo('approve_generus'))
            ])
            ->emptyStateHeading('Belum ada yang registrasi');
    }
}
