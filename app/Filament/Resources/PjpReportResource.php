<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PjpReportResource\Pages;
use App\Filament\Resources\PjpReportResource\RelationManagers;
use App\Models\PjpKegiatanReport;
use App\Models\PjpReport;
use App\Models\PjpSchedule;
use App\Traits\HandlesActiveRolePermission;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PjpReportResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = PjpReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan PJP';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                // --- BAGIAN II.a: KEGIATAN RUTIN ---
                Section::make('II.a Kegiatan Rutin')
                    ->description('Laporan kegiatan rutin bulanan berdasarkan jenjang/kelas')
                    ->schema([
                        Repeater::make('kegiatan_rutin')
                            ->relationship('kegiatan', modifyQueryUsing: fn($query) => $query->where('jenis_kegiatan', 'RUTIN')) // Tetap merujuk ke relasi kegiatan yang sama
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('jenis_kegiatan')
                                            ->label('Jenis Kegiatan')
                                            ->hidden()
                                            ->required(),
                                        TextInput::make('nm_kegiatan')
                                            ->label('Nama Kelas/Kegiatan')
                                            ->readOnly()
                                            ->required(),

                                        TextInput::make('jml_terlaksana')
                                            ->label('Terlaksana')
                                            ->numeric()
                                            ->suffix('pertemuan')
                                            ->placeholder('0')
                                            ->required(),

                                        TextInput::make('keterangan')
                                            ->label('Keterangan')
                                            ->placeholder('Keterangan singkat'),

                                        // Hidden field untuk menandai ini kegiatan rutin saat simpan
                                        Forms\Components\Hidden::make('jenis_kegiatan')
                                            ->default('rutin'),
                                    ]),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->afterStateHydrated(function (Repeater $component, ?PjpReport $record, $state) {
                                if (empty($state)) {
                                    $settings = PjpSchedule::find($record->pjp_schedule_id);

                                    $defaultData = [];
                                    foreach ($settings['kegiatan_rutin'] as $index => $setting) {
                                        $defaultData[$index]['jenis_kegiatan'] = 'RUTIN';
                                        $defaultData[$index]['nm_kegiatan'] = $setting;
                                    }

                                    $component->state($defaultData);
                                }
                            })
                    ]),

                // --- BAGIAN II.b: KEGIATAN KHUSUS ---
                Section::make('II.b Kegiatan Khusus')
                    ->description('Kegiatan di luar jadwal rutin (Seminar, Outbound, dll)')
                    ->schema([
                        Repeater::make('kegiatan_khusus')
                            ->relationship('kegiatan', modifyQueryUsing: fn($query) => $query->where('jenis_kegiatan', 'KHUSUS'))
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('nm_kegiatan')
                                            ->label('Nama Kegiatan')
                                            ->required(),

                                        DatePicker::make('tanggal')
                                            ->required(),

                                        TextInput::make('materi')
                                            ->columnSpanFull()
                                            ->required(),

                                        TextInput::make('peserta')
                                            ->placeholder('Contoh: Semua Generus')
                                            ->required(),

                                        FileUpload::make('dokumentasi')
                                            ->image()
                                            ->directory('pjp-khusus'),

                                        // Hidden field untuk menandai ini kegiatan khusus
                                        Forms\Components\Hidden::make('jenis_kegiatan')
                                            ->default('khusus'),
                                    ]),
                            ])
                            ->itemLabel(fn(array $state): ?string => $state['nm_kegiatan'] ?? 'Kegiatan Khusus Baru')
                            ->addActionLabel('Tambah Kegiatan Khusus')
                            ->collapsible(),
                    ]),

                // --- BAGIAN III: MUSYAWAROH ---
                Section::make('III. Musyawaroh')
                    ->description('Ceklis musyawaroh rutin bulanan')
                    ->schema([
                        Repeater::make('musyawaroh_rutin')
                            ->relationship('musyawaroh')
                            ->schema([
                                Forms\Components\Grid::make(3) // Membagi baris agar ringkas
                                    ->schema([
                                        // Field Kategori dibuat readonly/disabled agar user tidak mengubah nama musyawaroh rutinnya
                                        TextInput::make('judul_musyawaroh')
                                            ->label('Judul Musyawaroh')
                                            ->readOnly()
                                            ->required(),

                                        // Pengganti ceklis: Jika tidak diisi tanggal, dianggap belum musyawaroh
                                        // Atau bisa tambah field boolean 'is_done' jika di tabel ada
                                        DatePicker::make('tanggal')
                                            ->label('Tanggal Pelaksanaan')
                                            ->placeholder('Pilih tanggal jika terlaksana'),

                                        FileUpload::make('dokumentasi')
                                            ->label('Upload Foto')
                                            ->image()
                                            ->directory('pjp-musyawaroh')
                                            ->multiple()
                                            ->appendFiles(),
                                    ]),
                            ])
                            ->addable(false) // Mencegah user menambah baris baru di luar list rutin
                            ->deletable(false) // Mencegah user menghapus baris rutin
                            ->reorderable(false) // Agar urutan tetap konsisten
                            ->defaultItems(4) // Misal ada 4 musyawaroh rutin wajib
                            ->afterStateHydrated(function (Repeater $component, ?PjpReport $record, $state) {
                                if (empty($state)) {
                                    $settings = PjpSchedule::find($record->pjp_schedule_id);

                                    $defaultData = [];
                                    foreach ($settings['musyawaroh_rutin'] as $index => $setting) {
                                        $defaultData[$index]['judul_musyawaroh'] = $setting;
                                    }
                                    
                                    $component->state($defaultData);
                                }
                            })
                            ->mutateDehydratedStateUsing(function (array $state) {
                                // Opsional: Filter agar hanya data yang diisi tanggal saja yang masuk ke database
                                return array_filter($state, fn($item) => !empty($item['tanggal']));
                            }),
                    ]),

                Section::make('IV. Lainnya')
                    ->description('Keterangan tambahan yang ingin disampaikan, bisa berupa masukan, kendala, dan lain-lain')
                    ->schema([
                        Textarea::make('keterangan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pjpSchedule.bulan')
                    ->label('Bulan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pjpSchedule.tahun')
                    ->label('Tahun')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pjpSchedule.deadline_laporan')
                    ->label('Deadline')
                    ->dateTime('d M Y H:i')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'BELUM DIISI' => 'warning',
                        'SELESAI' => 'success',
                        'TIDAK LAPORAN' => 'danger'
                    })
                    ->searchable(),
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
                Tables\Actions\EditAction::make()
                    ->label('Isi Laporan')
                    ->hidden(fn(PjpReport $record) => $record->status === 'SELESAI'),
                Tables\Actions\Action::make('cetak_laporan')
                    ->label('PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn(PjpReport $record) => route('cetak.pjp', $record->id)) // Panggil nama route
                    ->openUrlInNewTab()
                    ->hidden(fn(PjpReport $record) => $record->status !== 'SELESAI'), // Sembunyikan jika belum selesai
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tidak ada data laporan')
            ->recordUrl(false);
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
            'index' => Pages\ListPjpReports::route('/'),
            'create' => Pages\CreatePjpReport::route('/create'),
            'edit' => Pages\EditPjpReport::route('/{record}/edit'),
        ];
    }
}
