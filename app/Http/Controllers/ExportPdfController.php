<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;

class ExportPdfController extends Controller
{
    public function rekapPresensiPdf(Event $event) {
        setlocale(LC_ALL, 'id-ID', 'id_ID');
        $eventParticipant = EventParticipant::query()->with('attendance')->where('event_id', $event->id)->get();
        $totalParticipant = $eventParticipant->count();
        $hadir = $eventParticipant->filter(fn($participant) => $participant->attendance?->arrival_status !== null)->count();
        $alfa = $totalParticipant - $hadir;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.rekap-presensi', compact('event', 'eventParticipant', 'totalParticipant', 'hadir', 'alfa'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream("Rekap Presensi {$event->name}.pdf");
    }
}
