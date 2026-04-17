<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PjpScheduleResource\Pages;
use App\Filament\Resources\PjpScheduleResource\RelationManagers;
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
                            ->default(now()->month)
                            ->required(),

                        Select::make('tahun')
                            ->options(
                                collect(range(now()->year, now()->year + 3))
                                    ->mapWithKeys(fn($year) => [$year => $year])
                            )
                            ->default(now()->year)
                            ->required(),
                    ])->columns(2),

                Section::make('PJP Desa')
                    ->schema([
                        DatePicker::make('deadline_desa')
                            ->required(),
                        Repeater::make('musyawaroh_desa')
                            ->label('Musyawaroh Rutin')
                            ->schema([
                                TextInput::make('judul_musyawaroh')
                                    ->required()
                                    ->label('Judul Musyawarah'),
                            ])
                            ->default(function () {
                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();

                                return $latest?->musyawaroh_rutin ?? [];
                            })
                            ->addActionLabel('Tambah Musyawarah')
                            ->collapsible()
                            ->columnSpanFull(),
                        Repeater::make('kegiatan_desa')
                            ->label('Kegiatan Rutin')
                            ->schema([
                                TextInput::make('nm_kegiatan')
                                    ->required()
                                    ->label('Nama Kegiatan'),
                            ])
                            ->default(function () {
                                if (request()->routeIs('*.edit')) {
                                    return null;
                                }

                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();

                                return $latest?->kegiatan_rutin ?? [];
                            })
                            ->addActionLabel('Tambah Kegiatan')
                            ->collapsible()
                            ->columnSpanFull(),

                    ]),

                Section::make('PJP Kelompok')
                    ->schema([
                        DatePicker::make('deadline_kelompok')
                            ->required(),
                        Repeater::make('musyawaroh_kelompok')
                            ->label('Musyawaroh Rutin')
                            ->schema([
                                TextInput::make('judul_musyawaroh')
                                    ->required()
                                    ->label('Judul Musyawarah'),
                            ])
                            ->default(function () {
                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();

                                return $latest?->musyawaroh_rutin ?? [];
                            })
                            ->addActionLabel('Tambah Musyawarah')
                            ->collapsible()
                            ->columnSpanFull(),
                        Repeater::make('kegiatan_kelompok')
                            ->label('Kegiatan Rutin')
                            ->schema([
                                TextInput::make('nm_kegiatan')
                                    ->required()
                                    ->label('Nama Kegiatan'),
                            ])
                            ->default(function () {
                                if (request()->routeIs('*.edit')) {
                                    return null;
                                }

                                $latest = \App\Models\PjpSchedule::getLatestForCurrentUser();

                                return $latest?->kegiatan_rutin ?? [];
                            })
                            ->addActionLabel('Tambah Kegiatan')
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),
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
