<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers\ParticipantsRelationManager;
use App\Helpers\RolePermission;
use App\Models\Event;
use App\Traits\HandlesActiveRolePermission;
use App\Traits\HandlesPermissionRelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;

class EventResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Events';

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

                    Select::make('attendance_method')
                        ->label('Metode Presensi')
                        ->options([
                            'manual' => 'Manual',
                            'rfid'   => 'RFID',
                            'manual_rfid' => 'Manual + RFID',
                        ])
                        ->required(),

                    Forms\Components\DatePicker::make('date')
                        ->label('Tanggal Pelaksanaan')
                        ->required(),

                    Forms\Components\TimePicker::make('start_time')
                        ->label('Waktu Mulai')
                        ->seconds(false)
                        ->minutesStep(15),
                    Forms\Components\TimePicker::make('end_time')
                        ->label('Waktu Selesai')
                        ->seconds(false)
                        ->minutesStep(15),
                ]),

                // konfigurasi kolom dinamis
                Repeater::make('column_config')
                    ->label('Konfigurasi Kolom Peserta')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Judul Kolom')
                            ->placeholder('Contoh : Nama Lengkap')
                            ->required(),
                        Forms\Components\TextInput::make('field')
                            ->label('Nama Kolom')
                            ->placeholder('Contoh : nama_lengkap')
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->label('Tipe Kolom')
                            ->options([
                                'string' => 'Tulisan/Teks',
                                'select' => 'Pilihan',
                                'number' => 'Angka',
                            ])->required(),
                    ])
                    ->columns(3)
                    ->required(),
                FileUpload::make('poster_image')
                    ->label('Poster Event')
                    ->disk('public')
                    ->directory('events')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('3:4')
                    ->imagePreviewHeight('250')
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth(600)
                    ->imageResizeTargetHeight(800)
                    ->directory('posters')
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
                    ->label('Tanggal Pelaksanaan')
                    ->date('d-m-Y'),
                TextColumn::make('name')
                    ->label('Nama Event')
                    ->searchable(),
                TextColumn::make('attendance_method')
                    ->label('Metode Presensi'),
                TextColumn::make('start_time')
                    ->label('Waktu Mulai')
                    ->time(),
                TextColumn::make('end_time')
                    ->label('Waktu Selesai')
                    ->time(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->emptyStateHeading('Belum ada data event');
    }

    public static function getRelations(): array
    {   
        return [
            ParticipantsRelationManager::class
        ];
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
