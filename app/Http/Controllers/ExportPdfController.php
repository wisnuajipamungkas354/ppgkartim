<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\PjpReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportPdfController extends Controller
{
    protected $month = [
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
        12 => 'Desember'
    ];

    public function rekapPresensiPdf(Event $event) {
        setlocale(LC_ALL, 'id-ID', 'id_ID');
        $eventParticipant = EventParticipant::query()->with('attendance')->where('event_id', $event->id)->get();
        $totalParticipant = $eventParticipant->count();
        $hadir = $eventParticipant->filter(fn($participant) => $participant->attendance?->arrival_status !== null)->count();
        $alfa = $totalParticipant - $hadir;

        $pdf = Pdf::loadView('pdf.rekap-presensi', compact('event', 'eventParticipant', 'totalParticipant', 'hadir', 'alfa'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream("Rekap Presensi {$event->name}.pdf");
    }

    public function laporanPjpPdf($id) {
        $getData = PjpReport::with(['pjpSchedule', 'kegiatan', 'musyawaroh'])->findOrFail($id);
        $kegiatanRutin = $getData->kegiatan->where('jenis_kegiatan', 'RUTIN')->values();
        $kegiatanKhusus = $getData->kegiatan->where('jenis_kegiatan', 'KHUSUS')->values();
        $musyawaroh = $getData->musyawaroh;
        $bulanTahun = $this->month[$getData->pjpSchedule->bulan] . ' ' . $getData->pjpSchedule->tahun;
        $logoImg = url('images/logo.png');
        $sensus = [
            'paud'       => ['l' => 12, 'p' => 15],
            'cbr'        => ['l' => 20, 'p' => 18],
            'pra_remaja' => ['l' => 15, 'p' => 20],
            'remaja'     => ['l' => 25, 'p' => 30],
            'pra_nikah'  => ['l' => 10, 'p' => 12],
        ];

        $arus = [
        'pindah'     => ['paud' => 0, 'cbr' => 1, 'pra_remaja' => 0, 'remaja' => 2, 'pra_nikah' => 0],
        'menikah'    => ['paud' => 0, 'cbr' => 0, 'pra_remaja' => 0, 'remaja' => 0, 'pra_nikah' => 1],
        'meninggal'  => ['paud' => 0, 'cbr' => 0, 'pra_remaja' => 0, 'remaja' => 0, 'pra_nikah' => 0],
        'amar_maruf' => ['paud' => 1, 'cbr' => 2, 'pra_remaja' => 0, 'remaja' => 1, 'pra_nikah' => 0],
        ];

        $kepengurusan = [
            (object) [
                'dapukan' => 'Ketua PJP',
                'nama_lengkap' => 'Abdul Fulan',
                'no_wa' => '0812-xxxx-xxxx'
            ],
            (object) [
                'dapukan' => 'Sekretaris',
                'nama_lengkap' => 'Budi Santoso',
                'no_wa' => '0813-xxxx-xxxx'
            ],
        ];

        $pdf = Pdf::loadView('pdf.laporan-pjp', compact('logoImg', 'bulanTahun', 'kegiatanRutin', 'kegiatanKhusus', 'musyawaroh', 'arus', 'kepengurusan', 'sensus'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Laporan PJP {$getData->pjpSchedule->bulan}_{$getData->pjpSchedule->tahun}.pdf");
    }
}
