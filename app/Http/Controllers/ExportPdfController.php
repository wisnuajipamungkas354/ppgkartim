<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\PjpReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\PjpPengurusReport;

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
        $logoPath = public_path('images/logo.png');
        $logoImg = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
        $isKelompok = $getData->reportable_type === 'App\Models\Kelompok';
        $reportableId = $getData->reportable_id;
        $namaLaporan = $isKelompok ? $getData->reportable->nm_kelompok : $getData->reportable->nm_desa;

        $generusCounts = \App\Models\Generus::whereHas('insan', function($q) use ($isKelompok, $reportableId) {
            if ($isKelompok) {
                $q->where('kelompok_id', $reportableId);
            } else {
                $q->where('desa_id', $reportableId);
            }
        })->with('insan:id,jk')->get();

        $sensus = [
            'paud'       => ['l' => 0, 'p' => 0],
            'cbr'        => ['l' => 0, 'p' => 0],
            'pra_remaja' => ['l' => 0, 'p' => 0],
            'remaja'     => ['l' => 0, 'p' => 0],
            'pra_nikah'  => ['l' => 0, 'p' => 0],
        ];

        $mapKategori = [
            'PAUD' => 'paud',
            'CABERAWIT' => 'cbr',
            'PRA_REMAJA' => 'pra_remaja',
            'REMAJA' => 'remaja',
            'PRA_NIKAH' => 'pra_nikah',
        ];

        foreach ($generusCounts as $g) {
            if (!$g->insan || !$g->insan->jk) continue;
            $jk = strtolower($g->insan->jk);
            if ($jk !== 'l' && $jk !== 'p') continue;
            
            $cat = $mapKategori[$g->kategori] ?? null;
            if ($cat) {
                $sensus[$cat][$jk]++;
            }
        }

        $arus = [
        'pindah'     => ['paud' => 0, 'cbr' => 1, 'pra_remaja' => 0, 'remaja' => 2, 'pra_nikah' => 0],
        'menikah'    => ['paud' => 0, 'cbr' => 0, 'pra_remaja' => 0, 'remaja' => 0, 'pra_nikah' => 1],
        'meninggal'  => ['paud' => 0, 'cbr' => 0, 'pra_remaja' => 0, 'remaja' => 0, 'pra_nikah' => 0],
        'amar_maruf' => ['paud' => 1, 'cbr' => 2, 'pra_remaja' => 0, 'remaja' => 1, 'pra_nikah' => 0],
        ];

        $kepengurusan = PjpPengurusReport::query()->where('pjp_report_id', $id)->get();

        $pdf = Pdf::loadView('pdf.laporan-pjp', compact('logoImg', 'bulanTahun', 'kegiatanRutin', 'kegiatanKhusus', 'musyawaroh', 'arus', 'kepengurusan', 'sensus', 'isKelompok', 'namaLaporan'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Laporan PJP {$getData->pjpSchedule->bulan}_{$getData->pjpSchedule->tahun}.pdf");
    }
}
