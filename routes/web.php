<?php

use App\Http\Controllers\ExportPdfController;
use App\Livewire\Counter;
use App\Livewire\Event\EventPresensi;
use App\Livewire\Event\PilihEvent;
use App\Livewire\Event\RekapPresensi;
use App\Livewire\Forms\ListRegistrasiForms;
use App\Livewire\Forms\RegistrasiGenerusForm;
use App\Livewire\LandingPage;
use App\Models\Event;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/counter', Counter::class);
Route::get('/', function() {
  return view('livewire.test');
});
Route::get('/registrasi-form', ListRegistrasiForms::class);
Route::get('/registrasi-generus-form', RegistrasiGenerusForm::class);
Route::get('/events', PilihEvent::class)->name('events');
Route::get('/event/{event}/presensi', EventPresensi::class)->name('events.presensi');
Route::get('/event/{event}/rekap', RekapPresensi::class)->name('events.rekap');
Route::get('/event/{event}/rekap/download', [ExportPdfController::class, 'rekapPresensiPdf'])->name('events.rekap.download');
Route::get('/laporan-pjp', function () {
  // 1. Siapkan Dummy Data
  $data = [
    'bulan' => 'Januari 2026',

    // Menggunakan (object) untuk mensimulasikan hasil query Eloquent Model
    'kegiatan_rutin' => [
      (object) [
        'nama_kegiatan' => 'Pengajian Caberawit',
        'jumlah_terlaksana' => '8 Kali',
        'keterangan' => 'Berjalan lancar di Masjid Baitul Makmur'
      ],
      (object) [
        'nama_kegiatan' => 'Pengajian Pra Remaja',
        'jumlah_terlaksana' => '2 Kali',
        'keterangan' => 'Dilaksanakan setiap minggu ke-2 dan ke-4'
      ],
    ],

    'kegiatan_khusus' => [
      (object) [
        'tanggal' => '2026-01-12', // Format YYYY-MM-DD agar Carbon bisa parse
        'nama_kegiatan' => 'Keakraban Pra Remaja',
        'materi' => 'Tata Krama & Akhlakul Karimah',
        'peserta' => 45,
        'dokumentasi' => ['dummy/foto1.jpg', 'dummy/foto2.jpg'] // Asumsi path JSON dari db
      ]
    ],

    'sensus' => [
      'paud'       => ['l' => 12, 'p' => 15],
      'cbr'        => ['l' => 20, 'p' => 18],
      'pra_remaja' => ['l' => 15, 'p' => 20],
      'remaja'     => ['l' => 25, 'p' => 30],
      'pra_nikah'  => ['l' => 10, 'p' => 12],
    ],

    'arus' => [
      'pindah'     => ['paud' => 0, 'cbr' => 1, 'pra_remaja' => 0, 'remaja' => 2, 'pra_nikah' => 0],
      'menikah'    => ['paud' => 0, 'cbr' => 0, 'pra_remaja' => 0, 'remaja' => 0, 'pra_nikah' => 1],
      'meninggal'  => ['paud' => 0, 'cbr' => 0, 'pra_remaja' => 0, 'remaja' => 0, 'pra_nikah' => 0],
      'amar_maruf' => ['paud' => 1, 'cbr' => 2, 'pra_remaja' => 0, 'remaja' => 1, 'pra_nikah' => 0],
    ],

    'kepengurusan' => [
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
    ],

    'musyawaroh' => [
      (object) [
        'judul_musyawaroh' => 'Musyawarah Evaluasi Program',
        'status' => 'Terlaksana',
        'tanggal' => '2026-01-05',
        'keterangan' => 'Membahas progres PJP',
        'dokumentasi' => ['dummy/rapat1.jpg']
      ]
    ]
  ];

  // 2. Load View dengan Data
  $pdf = Pdf::loadView('pdf.laporan-pjp', $data)
    ->setOptions(['isRemoteEnabled' => true]);

  // 3. Download PDF
  return $pdf->stream('Laporan-PJP');
})->name('cetak.pjp');
