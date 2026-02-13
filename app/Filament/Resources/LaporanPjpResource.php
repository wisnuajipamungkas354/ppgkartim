<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanPjpResource\Pages;
use App\Filament\Resources\LaporanPjpResource\RelationManagers;
use App\Models\LaporanPjp;
use App\Traits\HandlesActiveRolePermission;
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

class LaporanPjpResource extends Resource
{
    use HandlesActiveRolePermission;
    
    protected static ?string $model = LaporanPjp::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Laporan PJP';

    public static function form(Form $form): Form
    {
        $user = auth('web')->user();
        $musyawarohList = [];

        if($user->desa_id) {
            $musyawarohList = [
                ['judul_musyawaroh' => 'Musyawaroh PJP, Muda/i & Keputrian Desa'],
                ['judul_musyawaroh' => 'Musyawaroh Muda/i Desa'],
                ['judul_musyawaroh' => 'Laporan PJP Se-Desa'],
                ['judul_musyawaroh' => 'Laporan Pengurus Muda/i Kelompok ke Muda/i Desa'],
            ];
        } elseif($user->kelompok_id) {
            $musyawarohList = [
                ['judul_musyawaroh' => 'Musyawaroh PJP Kelompok'],
                ['judul_musyawaroh' => 'Musyawaroh 5 Unsur'],
                ['judul_musyawaroh' => 'Musyawaroh Muda/i Kelompok'],
                ['judul_musyawaroh' => 'Musyawaroh Dewan Guru'],
            ];
        }

        return $form
            ->schema([
                // --- BAGIAN I: INFORMASI UMUM ---
                Section::make('I. Informasi Umum')
                    ->description('Data utama laporan PJP')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('month')
                                    ->label('Bulan')
                                    ->options([
                                        '01' => 'Januari',
                                        '02' => 'Februari',
                                        '03' => 'Maret',
                                        '04' => 'April',
                                        '05' => 'Mei',
                                        '06' => 'Juni',
                                        '07' => 'Juli',
                                        '08' => 'Agustus',
                                        '09' => 'September',
                                        '10' => 'Oktober',
                                        '11' => 'November',
                                        '12' => 'Desember',
                                    ])
                                    ->required()
                                    ->default(date('m'))
                                    ->native(false), // Tampilan dropdown lebih modern
    
                                Select::make('year')
                                    ->label('Tahun')
                                    ->options(function () {
                                        $currentYear = date('Y');
                                        // Generate tahun dari 2 tahun lalu sampai 5 tahun ke depan
                                        return collect(range($currentYear - 2, $currentYear + 5))
                                            ->mapWithKeys(fn ($year) => [$year => $year])
                                            ->toArray();
                                    })
                                    ->default(date('Y'))
                                    ->required()
                                    ->native(false),
                            ]),
                    ])->columns(2),

                // --- BAGIAN II.a: KEGIATAN RUTIN ---
                Section::make('II.a Kegiatan Rutin')
                ->description('Laporan kegiatan rutin bulanan berdasarkan jenjang/kelas')
                ->schema([
                    Repeater::make('kegiatan_rutin')
                        ->relationship('kegiatan', modifyQueryUsing: fn ($query) => $query->where('jenis_kegiatan', 'rutin')) // Tetap merujuk ke relasi kegiatan yang sama
                        ->schema([
                            Grid::make(4)
                                ->schema([
                                    TextInput::make('nm_kegiatan')
                                        ->label('Nama Kelas/Kegiatan')
                                        ->readOnly()
                                        ->required(),

                                    TextInput::make('materi')
                                        ->placeholder('Judul materi...')
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
                        ->default([
                            ['nm_kegiatan' => 'Kelas PAUD/TK', 'jenis_kegiatan' => 'rutin'],
                            ['nm_kegiatan' => 'SD 1', 'jenis_kegiatan' => 'rutin'],
                            ['nm_kegiatan' => 'SD 2', 'jenis_kegiatan' => 'rutin'],
                            ['nm_kegiatan' => 'SD 3', 'jenis_kegiatan' => 'rutin'],
                            ['nm_kegiatan' => 'SD 4', 'jenis_kegiatan' => 'rutin'],
                            ['nm_kegiatan' => 'SD 5', 'jenis_kegiatan' => 'rutin'],
                            ['nm_kegiatan' => 'SD 6', 'jenis_kegiatan' => 'rutin'],
                            ['nm_kegiatan' => 'Pra remaja', 'jenis_kegiatan' => 'rutin'],
                            ['nm_kegiatan' => 'Remaja', 'jenis_kegiatan' => 'rutin'],
                            ['nm_kegiatan' => 'Pra nikah', 'jenis_kegiatan' => 'rutin'],
                        ]),
                ]),

                // --- BAGIAN II.b: KEGIATAN KHUSUS ---
                Section::make('II.b Kegiatan Khusus')
                ->description('Kegiatan di luar jadwal rutin (Seminar, Outbound, dll)')
                ->schema([
                    Repeater::make('kegiatan_khusus')
                        ->relationship('kegiatan', modifyQueryUsing: fn ($query) => $query->where('jenis_kegiatan', 'khusus'))
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
                        ->itemLabel(fn (array $state): ?string => $state['nm_kegiatan'] ?? 'Kegiatan Khusus Baru')
                        ->addActionLabel('Tambah Kegiatan Khusus')
                        ->collapsible(),
                ]),

                // --- BAGIAN III: MUSYAWAROH ---
                Section::make('III. Musyawaroh')
                    ->description('Ceklis musyawaroh rutin bulanan')
                    ->schema([
                        Repeater::make('musyawaroh')
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
                                            ->directory('pjp-musyawaroh'),
                                    ]),
                            ])
                            ->addable(false) // Mencegah user menambah baris baru di luar list rutin
                            ->deletable(false) // Mencegah user menghapus baris rutin
                            ->reorderable(false) // Agar urutan tetap konsisten
                            ->defaultItems(4) // Misal ada 4 musyawaroh rutin wajib
                            ->mutateDehydratedStateUsing(function (array $state) {
                                // Opsional: Filter agar hanya data yang diisi tanggal saja yang masuk ke database
                                return array_filter($state, fn($item) => !empty($item['tanggal']));
                            })
                            ->default($musyawarohList),
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
                Tables\Columns\TextColumn::make('reportable_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reportable_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('month')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tidak ada data laporan');
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
            'index' => Pages\ListLaporanPjps::route('/'),
            'create' => Pages\CreateLaporanPjp::route('/create'),
            'edit' => Pages\EditLaporanPjp::route('/{record}/edit'),
        ];
    }
}
