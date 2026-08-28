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

    public function laporanFgdPdf(\App\Models\FgdSession $session) {
        $notes = \App\Models\FgdNote::whereHas('group', function ($query) use ($session) {
                $query->where('fgd_session_id', $session->id);
            })
            ->with(['group', 'theme'])
            ->get();

        $logoCaiPath = public_path('images/logo-cai.webp');
        $logoPpgPath = public_path('images/logo.png');
        
        $logoCai = file_exists($logoCaiPath) ? 'data:image/webp;base64,' . base64_encode(file_get_contents($logoCaiPath)) : null;
        $logoPpg = file_exists($logoPpgPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPpgPath)) : null;

        $pdf = Pdf::loadView('pdf.fgd-session', [
            'session' => $session,
            'notes' => $notes,
            'logoCai' => $logoCai,
            'logoPpg' => $logoPpg,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_FGD_' . \Illuminate\Support\Str::slug($session->name) . '.pdf');
    }

    public function rekapPresensiPdf(Event $event) {
        setlocale(LC_ALL, 'id-ID', 'id_ID');
        $eventParticipant = EventParticipant::query()->with('attendances')->where('event_id', $event->id)->get();
        $totalParticipant = $eventParticipant->count();
        $hadir = $eventParticipant->filter(fn($participant) => $participant->attendances->isNotEmpty())->count();
        $alfa = $totalParticipant - $hadir;

        // Ambil semua sesi yang ada di event ini beserta jam mulainya
        $allSessions = [];
        if ($event->event_type === 'single') {
            $allSessions[] = [
                'label' => 'Sesi Tunggal',
                'start' => \Carbon\Carbon::parse($event->date . ' ' . $event->start_time)
            ];
        } else {
            foreach ($event->sessions ?? [] as $day) {
                if (isset($day['sesi'])) { // multi_day
                    foreach ($day['sesi'] as $sesi) {
                        $allSessions[] = [
                            'label' => $sesi['label'] ?? 'Sesi Unknown',
                            'start' => \Carbon\Carbon::parse(($day['date'] ?? '') . ' ' . ($sesi['start_time'] ?? '00:00:00'))
                        ];
                    }
                } else { // multi_session
                    $allSessions[] = [
                        'label' => $day['label'] ?? 'Sesi Unknown',
                        'start' => \Carbon\Carbon::parse($event->date . ' ' . ($day['start_time'] ?? '00:00:00'))
                    ];
                }
            }
        }
        
        // Fallback jika tidak ada sesi terdefinisi
        if (empty($allSessions)) {
            $allSessions[] = [
                'label' => 'Sesi Tunggal',
                'start' => \Carbon\Carbon::now() // Fallback
            ];
        }

        $headerTime = '';
        if ($event->event_type === 'single') {
            $headerTime = 'Jam ' . \Carbon\Carbon::parse($event->start_time)->format('H:i') . ' s/d ' . \Carbon\Carbon::parse($event->end_time)->format('H:i');
        } else {
            $headerTime = 'Semua Sesi';
        }

        // Kalkulasi Range Tanggal
        $dates = [];
        if ($event->event_type === 'multi_day') {
            foreach ($event->sessions ?? [] as $day) {
                if (!empty($day['date'])) {
                    $dates[] = $day['date'];
                }
            }
        }
        
        if (empty($dates)) {
            $dates[] = $event->date ?? now()->toDateString();
        }

        sort($dates);
        $startDate = \Carbon\Carbon::parse($dates[0])->locale('id');
        $endDate = \Carbon\Carbon::parse(end($dates))->locale('id');

        if ($startDate->isSameDay($endDate)) {
            $headerDate = $startDate->translatedFormat('d F Y');
        } elseif ($startDate->isSameMonth($endDate)) {
            $headerDate = $startDate->format('d') . ' - ' . $endDate->translatedFormat('d F Y');
        } elseif ($startDate->isSameYear($endDate)) {
            $headerDate = $startDate->translatedFormat('d F') . ' - ' . $endDate->translatedFormat('d F Y');
        } else {
            $headerDate = $startDate->translatedFormat('d F Y') . ' - ' . $endDate->translatedFormat('d F Y');
        }

        $pdf = Pdf::loadView('pdf.rekap-presensi', compact('event', 'eventParticipant', 'totalParticipant', 'hadir', 'alfa', 'allSessions', 'headerTime', 'headerDate'))
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
