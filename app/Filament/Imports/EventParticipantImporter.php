<?php

namespace App\Filament\Imports;

use App\Models\Event;
use App\Models\EventParticipant;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Http\Request;

class EventParticipantImporter extends Importer
{
    protected static ?string $model = EventParticipant::class;

    /**
     * Kolomnya kita buat dinamis berdasarkan column_config Event
     */
    public static function getColumns(): array
    {
        $snapshot = json_decode(request()->components[0]['snapshot']);
        $eventId = $snapshot->data->ownerRecord[1]->key;
    
        $event = Event::find($eventId);
    
        $columns = [
            ImportColumn::make('rfid_tag'),
        ];
    
        foreach ($event->column_config ?? [] as $config) {
            $columns[] = ImportColumn::make($config['field']);
        }
    
        return $columns;
    }

     /**
     *  override supaya tidak pakai mekanisme saveRecord default
     */
    public function saveRecord(): void
    {
        $snapshot = json_decode(request()->components[0]['snapshot'] ?? '{}');
        $eventId  = data_get($snapshot, 'data.ownerRecord.1.key');
        $event    = Event::find($eventId);

        $dataJson = [];
        foreach ($event->column_config ?? [] as $config) {
            $field = $config['field'];
            $dataJson[$field] = $this->data[$field] ?? null;
        }

        EventParticipant::create([
            'event_id'  => $eventId,
            'rfid_tag'  => $this->data['rfid_tag'] ?? null,
            'data_json' => $dataJson,
        ]);
    }

     // supaya resolveRecord tidak mencoba mencari record lama:
     public function resolveRecord(): ?EventParticipant
     {
         return new EventParticipant; // dummy instance only
     }

     public static function getCompletedNotificationBody(Import $import): string
     {
         return "Data peserta berhasil diimport.";
     }
}
