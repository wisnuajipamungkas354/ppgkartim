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
use App\Models\PjpReport;
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
Route::get('admin/pjp-reports/{id}/laporan-pjp', [ExportPdfController::class, 'laporanPjpPdf'])->name('cetak.pjp');
