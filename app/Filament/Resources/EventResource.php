<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers\ParticipantsRelationManager;
use App\Helpers\RolePermission;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Traits\HandlesActiveRolePermission;
use App\Traits\HandlesPermissionRelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Event';

    public static function getRecordIdentifier(): string
    {
        return 'id'; // nama kolom primary key
    }
    
    public static function form(Forms\Form $form): Forms\Form
    {
        $timeList = [
            '01:00','01:15','01:30','01:45',
            '02:00','02:15','02:30','02:45',
            '03:00','03:15','03:30','03:45',
            '04:00','04:15','04:30','04:45',
            '05:00','05:15','05:30','05:45',
            '06:00','06:15','06:30','06:45',
            '07:00','07:15','07:30','07:45',
            '08:00','08:15','08:30','08:45',
            '09:00','09:15','09:30','09:45',
            '10:00','10:15','10:30','10:45',
            '11:00','11:15','11:30','11:45',
            '08:00','08:15','08:30','08:45',
        ];
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Event')
                        ->placeholder('Masukkan nama event')
                        ->required(),

                    Forms\Components\TextInput::make('place')
                        ->label('Lokasi Event')
                        ->placeholder('Masukkan nama lokasi')
                        ->required(),

                    Select::make('event_type')
                        ->label('Jenis Kegiatan')
                        ->options([
                            'single'        => '1 Hari, 1 Sesi',
                            'multi_session' => '1 Hari, Beberapa Sesi',
                            'multi_day'     => 'Beberapa Hari (Multi Sesi)',
                        ])
                        ->default('single')
                        ->required()
                        ->live()
                        ->columnSpanFull()
                        ->helperText(fn (Get $get) => match($get('event_type')) {
                            'multi_session' => 'Satu tanggal dengan beberapa sesi waktu berbeda.',
                            'multi_day'     => 'Kegiatan berlangsung lebih dari satu hari, tiap hari bisa punya sesi berbeda.',
                            default         => 'Kegiatan berlangsung satu hari dengan satu sesi waktu.',
                        }),

                    // Tanggal: tampil untuk single & multi_session (bukan multi_day)
                    Forms\Components\DatePicker::make('date')
                        ->label('Tanggal Pelaksanaan')
                        ->required()
                        ->visible(fn (Get $get) => in_array($get('event_type') ?? 'single', ['single', 'multi_session']))
                        ->columnSpanFull(),

                    // Waktu: hanya untuk single
                    Forms\Components\TimePicker::make('start_time')
                        ->label('Waktu Mulai')
                        ->seconds(false)
                        ->visible(fn (Get $get) => ($get('event_type') ?? 'single') === 'single'),

                    Forms\Components\TimePicker::make('end_time')
                        ->label('Waktu Selesai')
                        ->seconds(false)
                        ->visible(fn (Get $get) => ($get('event_type') ?? 'single') === 'single'),
                ]),
                
                Forms\Components\Fieldset::make('Pengaturan Presensi')
                    ->schema([
                        Forms\Components\Select::make('open_attendance_before')
                            ->label('Presensi Dibuka (Sebelum Acara)')
                            ->options([
                                5 => '5 Menit Sebelum Acara',
                                10 => '10 Menit Sebelum Acara',
                                15 => '15 Menit Sebelum Acara',
                                30 => '30 Menit Sebelum Acara',
                                60 => '1 Jam Sebelum Acara',
                            ])
                            ->default(30)
                            ->required(),
                        
                        Forms\Components\Select::make('late_tolerance')
                            ->label('Toleransi Keterlambatan (Setelah Mulai)')
                            ->options([
                                5 => '5 Menit',
                                10 => '10 Menit',
                                15 => '15 Menit',
                                20 => '20 Menit',
                                30 => '30 Menit',
                            ])
                            ->default(10)
                            ->required(),
                    ])->columns(2),

                // ── MULTI SESSION: sesi-sesi dalam 1 hari ──
                Repeater::make('sessions')
                    ->label('Sesi Kegiatan')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Nama Sesi')
                            ->placeholder('Contoh: Sesi Pagi')
                            ->required(),
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Waktu Mulai')
                            ->seconds(false)
                            ->required(),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('Waktu Selesai')
                            ->seconds(false)
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Sesi')
                    ->minItems(1)
                    ->visible(fn (Get $get) => $get('event_type') === 'multi_session'),

                // ── MULTI DAY: tiap hari punya sesinya sendiri ──
                // Nama field 'days' (bukan 'sessions') untuk hindari duplikat.
                // Di handleRecordCreation, 'days' akan dipindah ke kolom 'sessions'.
                Repeater::make('days')
                    ->label('Hari Kegiatan')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->columnSpanFull(),
                        Repeater::make('sesi')
                            ->label('Sesi')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Nama Sesi')
                                    ->placeholder('Contoh: Sesi Pagi')
                                    ->required(),
                                Forms\Components\TimePicker::make('start_time')
                                    ->label('Waktu Mulai')
                                    ->seconds(false)
                                    ->required(),
                                Forms\Components\TimePicker::make('end_time')
                                    ->label('Waktu Selesai')
                                    ->seconds(false)
                                    ->required(),
                            ])
                            ->columns(3)
                            ->addActionLabel('Tambah Sesi')
                            ->minItems(1)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Hari')
                    ->minItems(1)
                    ->visible(fn (Get $get) => $get('event_type') === 'multi_day'),

                // konfigurasi kolom dinamis
                Repeater::make('column_config')
                    ->label('Konfigurasi Kolom Peserta')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Judul Kolom')
                            ->placeholder('Contoh : Nama Lengkap')
                            ->required()
                            ->live(debounce: 400)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $set('field', Str::snake($state ?? ''));
                            }),
                        Forms\Components\TextInput::make('field')
                            ->label('Nama Kolom')
                            ->placeholder('Contoh : nama_lengkap')
                            ->required()
                            ->live(debounce: 300)
                            ->afterStateUpdated(fn (Set $set, ?string $state) =>
                                $set('field', strtolower(preg_replace('/[^a-z0-9_]/', '', strtolower($state ?? '')))
                            ))
                            ->rules(['regex:/^[a-z][a-z0-9_]*$/'])
                            ->validationMessages(['regex' => 'Hanya boleh huruf kecil, angka, dan underscore (_).']),
                        Forms\Components\Select::make('type')
                            ->label('Tipe Kolom')
                            ->options([
                                'string' => 'Tulisan/Teks',
                                'select' => 'Pilihan',
                                'number' => 'Angka',
                                'boolean' => 'Ya/Tidak',
                            ])
                            ->live()
                            ->required(),
                        Forms\Components\Repeater::make('options')
                            ->label('Buat Pilihan')
                            ->simple(Forms\Components\TextInput::make('option_name')
                                    ->label('Nama Pilihan')
                                    ->required())
                            ->visible(fn(Get $get) => $get('type') === 'select'),
                        Forms\Components\Toggle::make('searchable')
                            ->label('Dapat dicari ?'),
                        Forms\Components\Toggle::make('filterable')
                            ->label('Dapat difilter ?')
                            ->visible(fn(Get $get) => $get('type') === 'select'),
                        Forms\Components\Toggle::make('hidden')
                            ->label('Sembunyikan kolom secara default ?'),
                ])
                ->columns(3)
                ->columnSpanFull()
                ->addActionLabel('Tambah Kolom')
                ->required(),
                FileUpload::make('poster_image')
                    ->label('Poster Event')
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('3:4')
                    ->imagePreviewHeight('250')
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth(600)
                    ->imageResizeTargetHeight(800)
                    ->directory('events/posters')
                    ->maxSize(2048) // ukuran maksimal 2MB
                    ->hint('Ukuran maksimal 2MB. Rasio 3:4 (potrait)')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d-m-Y'),
                TextColumn::make('name')
                    ->label('Nama Event')
                    ->searchable(),
                TextColumn::make('kode_event')
                    ->label('Kode Event')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode event berhasil di salin')
                    ->copyMessageDuration(1500),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),                    

            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('qr-download')
                ->label('QR-Code')
                ->url(function(Event $record): string {
                    $path = 'public/events/qr-images/'. $record->id . '.png';
                    $url = Storage::url($path);
                    return $url;
                })
                ->extraAttributes(fn(Event $record) => ['download' => $record->name])
                ->icon('heroicon-s-qr-code')
                ->color('info'),
                Tables\Actions\Action::make('duplikat')
                    ->label('Duplikat')
                    ->action(function(Event $record) {
                        $newEvent = Event::create([
                            'user_id' => $record->user_id,
                            'role_id' => $record->role_id,
                            'poster_image' => $record->poster_image,
                            'name' => $record->name,
                            'place' => $record->place,
                            'date' => $record->date,
                            'event_type' => $record->event_type,
                            'sessions' => $record->sessions,
                            'column_config' => $record->column_config,
                            'kode_event' => $record->kode_event,
                            'is_active' => $record->is_active,
                        ]);

                        $copiesParticipant = EventParticipant::where('event_id', $record->id)->get();

                        foreach($copiesParticipant as $copy) {
                            EventParticipant::create([
                                'event_id' => $newEvent->id,
                                'rfid_tag' => $copy->rfid_tag,
                                'data_json' => $copy->data_json,
                            ]);
                        }
                    })
                    ->successNotification(fn(Notification $notification) => $notification->title('Berhasil di Duplikat')),
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Event')
                    ->modalDescription('Apakah kamu yakin ingin menghapus event ini ?')
                    ->modalSubmitActionLabel('Ya')
                    ->modalCancelActionLabel('Batal')
                    ->action(function(array $data, Event $record) {
                        if($record->poster_image != null) Storage::delete($record->poster_image);
                        $record->delete();
                    })
                    ->successNotification(fn(Notification $notification) => $notification->title('Dihapus')),
            ])
            ->emptyStateHeading('Belum ada data event');
    }

    public static function getRelations(): array
    {   
        return [
            ParticipantsRelationManager::class
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->user()->id)->where('role_id', session('active_role_id'))->orderBy('created_at', 'DESC');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit'   => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
