<?php

namespace App\Filament\Pages;

use App\Filament\Resources\GenerusResource\Pages\Forms\GenerusForm;
use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\Insan;
use App\Models\Mubaligh;
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
use Illuminate\Database\Eloquent\Builder;
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
                TextColumn::make('insan.desa.nm_desa')
                    ->label('Desa')
                    ->formatStateUsing(fn (string $state) => Str::title($state)),
                TextColumn::make('insan.kelompok.nm_kelompok')
                    ->label('Kelompok')
                    ->formatStateUsing(fn (string $state) => Str::title($state)),
                TextColumn::make('insan.nama')
                    ->label('Nama Lengkap')
                    ->formatStateUsing(fn (string $state) => Str::title($state))
                    ->searchable(),
                TextColumn::make('insan.jk')
                    ->label('L/P')
                    ->sortable(),
                TextColumn::make('insan.kota_lahir')
                    ->label('Kota Lahir')
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
                            ->schema(fn(Get $get) => GenerusForm::getForms($get)),
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
                        $mts = '';
                        $isMT = Mubaligh::where('insan_id', $record->insan->id)->first();
                        $isMs = Mubaligh::where('insan_id', $record->insan->id)->first();
                        if($isMT) {
                            $mts = 'MT';
                        } elseif($isMs) {
                            $mts = 'MS';
                        } else {
                            $mts = 'BUKAN';
                        }

                        return [
                            'daerah_id' => $record->insan->daerah_id,
                            'desa_id' => $record->insan->desa_id,
                            'kelompok_id' => $record->insan->kelompok_id,
                            'nama' => $record->insan->nama,
                            'jk' => $record->insan->jk,
                            'kota_lahir' => $record->insan->kota_lahir,
                            'tgl_lahir' => $record->insan->tgl_lahir,
                            'gol_dar' => $record->insan->gol_dar,
                            'no_hp' => $record->insan->no_hp,
                            'pendidikan_terakhir' => $record->insan->pendidikan_terakhir,
                            'jurusan' => $record->insan->jurusan,
                            'nm_ayah' => $record->insan->nm_ayah,
                            'nm_ibu' => $record->insan->nm_ibu,
                            'no_hp_wali' => $record->insan->no_hp_wali,
                            'minat_id' => $record->insan->minat_id,
                            'detail_minat' => $record->insan->detail_minat,
                            'siap_nikah' => $record->insan->siap_nikah,
                            'nis' => $record->nis,
                            'jenis_data' => $record->jenis_data,
                            'kategori' => $record->kategori,
                            'kelas_ppg_id' => $record->kelas_ppg_id,
                            'mubaligh' => $mts, 
                            'tingkatan_tugas' => $record->detail_status['tingkatan_tugas'] ?? null, 
                            'tgl_mulai_tugas' => $record->detail_status['tgl_mulai_tugas'] ?? null, 
                            'asal_pondok' => $record->detail_status['asal_pondok'] ?? null, 
                            'tugasan_ke' => $record->detail_status['tugasan_ke'] ?? null, 
                            'jml_tugas' => $record->detail_status['jml_tugas'] ?? null, 
                            'lama_tugas' => $record->detail_status['lama_tugas'] ?? null, 
                            'status_id' => $record->status_id,
                            'program_studi' => $record->detail_status['program_studi'] ?? null, 
                            'universitas' => $record->detail_status['universitas'] ?? null, 
                            'jabatan' => $record->detail_status['jabatan'] ?? null, 
                            'nm_perusahaan' => $record->detail_status['nm_perusahaan'] ?? null, 
                            'bidang_usaha' => $record->detail_status['bidang_usaha'] ?? null, 
                            'nm_usaha' => $record->detail_status['nm_usaha'] ?? null, 
                            'keahlian' => $record->detail_status['keahlian'] ?? null,
                            'nm_sekolah' => $record->detail_status['nm_sekolah'] ?? null,
                            'kelas_di_sekolah' => $record->detail_status['kelas_di_sekolah'] ?? null,
                            'peminatan_sekolah' => $record->detail_status['peminatan_sekolah'] ?? null,
                            'is_verified' => $record->is_verified,
                            'riwayat_update' => $record->riwayat_update,
                        ];
                    })
                    ->action(function(array $data, Generus $record) {
                        // Update Data
                        $data['usia'] = Carbon::parse($data['tgl_lahir'])->age ?? null;
                        $data['riwayat_update'] = 'APPROVED';
                        $data['is_verified'] = true;
                        
                        $insan = Insan::find($record->insan->id);
                        
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
                        $insan = Insan::find($record->insan->id);
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

    public static function getNavigationBadge(): ?string
    {
        return Generus::where('is_verified', false)->count();
    }
}
