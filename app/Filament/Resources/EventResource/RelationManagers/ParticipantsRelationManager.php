<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Filament\Imports\EventParticipantImporter;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Generus;
use App\Models\MubalighSetempat;
use App\Models\MubalighTugasan;
use App\Traits\HandlesActiveRolePermission;
use App\Traits\HandlesPermissionRelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Model;

class ParticipantsRelationManager extends RelationManager implements HasShieldPermissions
{
    protected static string $relationship = 'participants';
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $title = 'Peserta';

    public static function canViewForRecord($ownerRecord, $pageClass): bool
    {
        return true;
    }
    
    public static function canCreateForRecord($ownerRecord, $pageClass): bool
    {
        return true;
    }

    protected function canCreate(): bool
    {
        return true;
    }

    protected function canEdit(Model $record): bool
    {
        return true;
    }

    protected function canDelete(Model $record): bool
    {
        return true;
    }
    
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
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
            'attach',
            'detach',
            'detach_any',
            'associate',
            'associate_any',
            'dissociate',
            'dissociate_any',
        ];
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rfid_tag')
                    ->label('RFID Tag')
                    ->unique(ignoreRecord: true),

                Forms\Components\KeyValue::make('data_json')
                    ->label('Data Peserta')
                    ->keyLabel('Field')
                    ->valueLabel('Value'),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(EventParticipantImporter::class)
                    ->label('Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->modalHeading('Import Data Peserta dari Excel'),
                Action::make('import_from_db')
                    ->label('Database')
                    ->icon('heroicon-o-circle-stack')
                    ->color('info')
                    ->form([
                        // pilihan sumber tabel
                        Select::make('source')
                            ->label('Ambil Dari')
                            ->options([
                                'generus' => 'Generus',
                                'ms'      => 'Mubaligh Setempat',
                                'customers' => 'Customers',
                            ])
                            ->live()
                            ->required(),
                
                        // pilihan data dari tabel sumber (dinamis berdasarkan source)
                        Select::make('source_ids')
                            ->label('Pilih Data')
                            ->multiple()
                            ->options(function (callable $get) {
                                return match ($get('source')) {
                                    'generus' => Generus::all(),
                                    'mt'      => MubalighTugasan::pluck('nama', 'id'),
                                    'ms'      => MubalighSetempat::pluck('nama', 'id'),
                                    default  => [],
                                };
                            })
                            ->required(),
                    ])
                    ->action(function ($data) {
                        $event = $this->ownerRecord;
                        $ids   = $data['source_ids'];
                        $source= $data['source'];
                
                        $collection = match ($source) {
                            'generus' => Generus::all(),
                            'mt'      => MubalighTugasan::whereIn('id', $ids)->get(),
                            'ms'      => MubalighSetempat::whereIn('id', $ids)->get(),
                            default   => collect(),
                        };
                
                        foreach ($collection as $item) {
                            EventParticipant::firstOrCreate([
                                'event_id'  => $event->id,
                            ],[
                                'rfid_tag'  => $item->rfid_tag ?? null,
                                'data_json' => [
                                    'nama' => $item->nama_lengkap ?? $item->nama ?? $item->name ?? null,
                                    'jk'   => $item->jk ?? $item->gender ?? null,
                                    // bisa mapping lebih banyak field
                                ]
                            ]);
                        }
                    }),
                CreateAction::make()
                    ->label('Manual')
                    ->icon('heroicon-o-plus'),
            ])
            ->description('Data peserta dapat diimport dari excel, dan database. Bisa juga ditambahkan secara manual')
            ->columns(self::getDynamicColumns())
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data')
                    ->modalDescription('Apakah kamu yakin ingin menghapus peserta ini ?')
                    ->modalSubmitActionLabel('Ya')
                    ->modalCancelActionLabel('Batal')
                    ->successNotification(fn(Notification $notification) => $notification->title('Dihapus')),
            ]);
    } 
    
    protected static function getDynamicColumns(): array
    {
        /** @var \App\Models\Event $event */
        $snapshot = json_decode(request()->components[0]['snapshot'] ?? '{}');
        $eventId  = data_get($snapshot, 'data.ownerRecord.1.key');
        $event    = Event::find($eventId);

        // Kolom default yg ingin selalu ditampilkan
        $columns = [
            Tables\Columns\TextColumn::make('rfid_tag')->label('RFID Tag')
                ->sortable()
                ->searchable(),
        ];

        // Tambahkan kolom dinamis berdasarkan column_config
        foreach ($event->column_config ?? [] as $config) {
            $field = $config['field'];
            $label = $config['label'];

            // Menampilkan data_json->$field
            $columns[] = Tables\Columns\TextColumn::make("data_json.{$field}")
                ->label($label)
                ->sortable()
                ->searchable();
        }

        return $columns;
    }
}
