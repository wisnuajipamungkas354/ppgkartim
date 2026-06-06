<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PjpScheduleResource\Pages;
use App\Filament\Resources\PjpScheduleResource\RelationManagers;
use App\Helpers\AccessHelper;
use App\Models\PjpSchedule;
use App\Traits\HandlesActiveRolePermission;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PjpScheduleResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = PjpSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Monitoring';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Jadwal')
                    ->schema([
                        Select::make('bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ])
                            ->required()
                            ->disabled(fn() => !AccessHelper::isDaerah()),

                        Select::make('tahun')
                            ->options(
                                collect(range(now()->year, now()->year + 3))
                                    ->mapWithKeys(fn($year) => [$year => $year])
                            )
                            ->default(now()->year)
                            ->required()
                            ->disabled(fn() => !AccessHelper::isDaerah()),
                    ])->columns(2),

                Section::make('PJP Kelompok')
                    ->schema([
                        DatePicker::make('deadline_laporan')
                            ->required()
                            ->disabled()
                            ->hidden(fn() => AccessHelper::isDaerah()),
                        DatePicker::make('deadline_kelompok')
                            ->required()
                            ->hidden(fn() => !AccessHelper::isDaerah()),
                        Repeater::make('musyawaroh_kelompok')
                            ->label('Musyawaroh Rutin')
                            ->simple(
                                TextInput::make('judul_musyawaroh')
                                    ->required()
                                    ->label('Judul Musyawarah'),
                            )
                            ->default(function () {
                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();
                                $newData = $latest?->toArray();

                                return $newData['list_laporan']['musyawaroh_kelompok'] ?? [
                                    'Musyawarah 5 Unsur',
                                    'Musyawarah PJP, Muda/i dan Keputrian Kelompok',
                                    'Musyawarah Muda/i Kelompok',
                                ];
                            })
                            ->addActionLabel('Tambah Musyawarah')
                            ->collapsible()
                            ->disabled(fn() => !AccessHelper::isDaerah())
                            ->addable(fn() => AccessHelper::isDaerah())
                            ->deletable(fn() => AccessHelper::isDaerah())
                            ->reorderable(fn() => AccessHelper::isDaerah())
                            ->columnSpanFull(),
                        Repeater::make('kegiatan_kelompok')
                            ->label('Kegiatan Rutin')
                            ->simple(
                                TextInput::make('nm_kegiatan')
                                    ->required()
                                    ->label('Nama Kegiatan'),
                            )
                            ->default(function () {

                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();
                                $newData = $latest?->toArray();

                                return $newData['list_laporan']['kegiatan_kelompok'] ?? [
                                    'Pengajian Paud/TK',
                                    'Pengajian Caberawit (SD Kelas 1-6)',
                                    'Pengajian Pra Remaja (SMP)',
                                    'Pengajian Remaja (SMA)',
                                    'Pengajian Muda/i (Umum)',
                                ];
                            })
                            ->addActionLabel('Tambah Kegiatan')
                            ->disabled(fn() => !AccessHelper::isDaerah())
                            ->deletable(fn() => AccessHelper::isDaerah())
                            ->reorderable(fn() => AccessHelper::isDaerah())
                            ->collapsible()
                            ->columnSpanFull(),
                        Repeater::make('pengurus_pjp_kelompok')
                            ->label('Pengurus PJP')
                            ->simple(
                                TextInput::make('nm_dapukan')
                                    ->label('Nama Dapukan')
                                    ->required()
                            )
                            ->default(function () {

                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();
                                $newData = $latest?->toArray();

                                return $newData['list_laporan']['pengurus_pjp_kelompok'] ?? [
                                    'Pembina',
                                    'Ketua PJP',
                                    'Wakil Ketua PJP',
                                ];
                            })
                            ->addActionLabel('Tambah Dapukan')
                            ->disabled(fn() => !AccessHelper::isDaerah())
                            ->addable(fn() => AccessHelper::isDaerah())
                            ->deletable(fn() => AccessHelper::isDaerah())
                            ->reorderable(fn() => AccessHelper::isDaerah()),
                        Repeater::make('pengurus_lima_unsur_kelompok')
                            ->label('Pengurus 5 Unsur')
                            ->simple(
                                TextInput::make('nm_dapukan')
                                    ->label('Nama Dapukan')
                                    ->required()
                            )
                            ->default(function () {
                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();
                                $newData = $latest?->toArray();

                                return $newData['list_laporan']['pengurus_lima_unsur_kelompok'] ?? [
                                    'Pembina',
                                    'Mubaligh',
                                    'Pengurus PJP',
                                    'Pakar Pendidik',
                                    'Orang Tua',
                                ];
                            })
                            ->addActionLabel('Tambah List Dapukan')
                            ->disabled(fn() => !AccessHelper::isDaerah())
                            ->addable(fn() => AccessHelper::isDaerah())
                            ->deletable(fn() => AccessHelper::isDaerah())
                            ->reorderable(fn() => AccessHelper::isDaerah()),
                        Repeater::make('kegiatan_tambahan')
                            ->label('Kegiatan Tambahan')
                            ->simple(
                                TextInput::make('nm_kegiatan')
                                    ->required()
                                    ->label('Nama Kegiatan'),
                            )
                            ->default(function () {

                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();
                                $newData = $latest?->toArray();

                                return $newData['list_laporan']['kegiatan_tambahan'] ?? [];
                            })
                            ->addActionLabel('Tambah Kegiatan')
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Section::make('PJP Desa')
                    ->schema([
                        DatePicker::make('deadline_desa')
                            ->required(),
                        Repeater::make('musyawaroh_desa')
                            ->label('Musyawaroh Rutin')
                            ->simple(
                                TextInput::make('judul_musyawaroh')
                                    ->required()
                                    ->label('Judul Musyawarah'),
                            )
                            ->default(function () {
                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();
                                $newData = $latest?->toArray();

                                return $newData['list_laporan']['musyawaroh_desa'] ?? [
                                    'Musyawarah PJP, Muda/i dan Keputrian Desa',
                                    'Musyawarah Muda/i Desa',
                                    'Laporan PJP Kelompok ke PJP Desa',
                                ];
                            })
                            ->addActionLabel('Tambah Musyawarah')
                            ->collapsible()
                            ->hidden(fn() => !AccessHelper::isDaerah())
                            ->columnSpanFull(),
                        Repeater::make('kegiatan_desa')
                            ->label('Kegiatan Rutin')
                            ->simple(
                                TextInput::make('nm_kegiatan')
                                    ->required()
                                    ->label('Nama Kegiatan'),
                            )
                            ->default(function () {

                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();
                                $newData = $latest?->toArray();

                                return $newData['list_laporan']['kegiatan_desa'] ?? [
                                    'Pengajian Muda/i Desa',
                                ];
                            })
                            ->addActionLabel('Tambah Kegiatan')
                            ->collapsible()
                            ->columnSpanFull(),
                        Repeater::make('pengurus_pjp_desa')
                            ->label('Pengurus PJP Desa')
                            ->simple(
                                TextInput::make('nm_dapukan')
                                    ->label('Nama Dapukan')
                                    ->required()
                            )
                            ->default(function () {

                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();
                                $newData = $latest?->toArray();
                                return $newData['list_laporan']['pengurus_pjp_desa'] ?? [
                                    'Pembina',
                                    'Ketua PJP',
                                    'Wakil Ketua PJP',
                                ];
                            })
                            ->addActionLabel('Tambah List Dapukan')
                            ->hidden(fn() => !AccessHelper::isDaerah()),

                    ])
                    ->visible(fn() => AccessHelper::isDaerah()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bulan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline_laporan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'DIBUKA' => 'success',
                        'DRAFT' => 'warning',
                        'DITUTUP' => 'DANGER'
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('share')
                    ->label('Share')
                    ->icon('heroicon-o-share')
                    ->color('success')
                    ->visible(fn($record) => $record->is_closed === 0 ? true : false)
                    ->action(function ($record) {

                        $bulanNama = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ][$record->bulan];

                        $message  = "🙏🏻 السلام عليكم ورحمة الله وبركاته 🙏🏻\n\n";
                        $message .= "📢 *Menginformasikan*\n\n";

                        $message .= "*Kepada Seluruh Ketua PJP,*\n";
                        $message .= "Mohon amal sholih untuk segera mengisi Laporan PJP periode bulan *{$bulanNama} {$record->tahun}* pada link berikut: \n\n";
                        $message .= url('admin/pjp-reports') . "\n\n";
                        $message .= "NB:\n";
                        $message .= "- Deadline : " . $record->deadline_laporan->format('d M Y H:i') . "\n\n";

                        $message .= "Ditetapi dan dikerjakan dengan sakpol kemampuan karena Allah.\n\n";
                        $message .= "Semoga Allah paring kesemangatan, keamanan, kelancaran, kesuksesan dan kebarokahan.\n\n";

                        $message .= "الحمد لله جزاكم الله خيرا 😊🙏🏻";

                        $encoded = rawurlencode($message);

                        $url = "https://api.whatsapp.com/send?text={$encoded}";

                        return redirect()->away($url);
                    }),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Jadwal Laporan PJP')
                    ->modalSubmitActionLabel('Simpan Perubahan')
                    ->mountUsing(function(Form $form, PjpSchedule $pjpSchedule) {
                        $newData = $pjpSchedule->toArray();

                        if(AccessHelper::isDaerah()) {
                            $newData['musyawaroh_desa'] = $pjpSchedule['list_laporan']['musyawaroh_desa'];
                            $newData['kegiatan_desa'] = $pjpSchedule['list_laporan']['kegiatan_desa'];
                            $newData['pengurus_pjp_desa'] = $pjpSchedule['list_laporan']['pengurus_pjp_desa']; 
                        } elseif(AccessHelper::isDesa()) {
                            $newData['musyawaroh_kelompok'] = $pjpSchedule['list_laporan']['musyawaroh_kelompok'];
                            $newData['kegiatan_kelompok'] = $pjpSchedule['list_laporan']['kegiatan_kelompok'];
                            $newData['pengurus_pjp_kelompok'] = $pjpSchedule['list_laporan']['pengurus_pjp_kelompok'];
                            $newData['pengurus_lima_unsur_kelompok'] = $pjpSchedule['list_laporan']['pengurus_lima_unsur_kelompok'];
                        }
                        unset($newData['list_laporan']);
                        unset($newData['created_at']);
                        unset($newData['updated_at']);
                        
                        $form->fill($newData);
                    })
                    ->action(function (array $data, PjpSchedule $pjpSchedule) {
                        $data['status'] = 'DIBUKA';
                        if(AccessHelper::isDaerah()) {
                            $data['list_laporan'] = [
                                'musyawaroh_desa' => $data['musyawaroh_desa'],
                                'kegiatan_desa' => $data['kegiatan_desa'],
                                'pengurus_pjp_desa' => $data['pengurus_pjp_desa'],
                            ];
                        } elseif(AccessHelper::isDesa()) {
                            $newData = $pjpSchedule->toArray();
                            $data['list_laporan'] = [
                                'musyawaroh_kelompok' => $newData['list_laporan']['musyawaroh_kelompok'],
                                'kegiatan_kelompok' => $newData['list_laporan']['kegiatan_kelompok'],
                                'pengurus_pjp_kelompok' => $newData['list_laporan']['pengurus_pjp_kelompok'],
                                'pengurus_lima_unsur_kelompok' => $newData['list_laporan']['pengurus_lima_unsur_kelompok'],
                                'kegiatan_tambahan' => $data['kegiatan_tambahan'],
                            ];

                            $data['status'] = 'DIBUKA';
                        }

                        $pjpSchedule->update($data);
                    })
                    ->hidden(fn($record) => $record->is_closed === 1),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus')
                    ->modalDescription('Apakah kamu yakin akan menghapus data ini?')
                    ->modalSubmitActionLabel('Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->successNotificationTitle('Berhasil dihapus!')
                    ->hidden(fn($record) => $record->is_closed === 1),
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
        return parent::getEloquentQuery()->owned();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePjpSchedules::route('/'),
        ];
    }
}
