<?php

use App\Http\Controllers\ExportPdfController;
use App\Http\Controllers\LandingController;
use App\Livewire\Counter;
use App\Livewire\Event\EventPresensi;
use App\Livewire\Event\PilihEvent;
use App\Livewire\Event\RekapPresensi;
use App\Livewire\Forms\ListRegistrasiForms;
use App\Livewire\Forms\RegistrasiGenerusForm;
use App\Models\PjpReport;
use Illuminate\Support\Facades\Route;

// Landing Page (publik)
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/api/chart-data', [LandingController::class, 'chartData'])->name('api.chart-data');
Route::get('/api/detail-generus', [LandingController::class, 'detailGenerus'])->name('api.detail-generus');

// Registrasi & Event (publik)
Route::get('/counter', Counter::class);
Route::get('/registrasi-form', ListRegistrasiForms::class);
Route::get('/registrasi-generus-form', RegistrasiGenerusForm::class);
Route::get('/events', PilihEvent::class)->name('events');
Route::get('/event/{event}/presensi', EventPresensi::class)->name('events.presensi');
Route::get('/event/{event}/rekap', RekapPresensi::class)->name('events.rekap');
Route::get('/event/{event}/rekap/download', [ExportPdfController::class, 'rekapPresensiPdf'])->name('events.rekap.download');
Route::get('admin/pjp-reports/{id}/laporan-pjp', [ExportPdfController::class, 'laporanPjpPdf'])->name('cetak.pjp');
Route::get('admin/fgd-sessions/{session}/pdf', [ExportPdfController::class, 'laporanFgdPdf'])->name('cetak.fgd-session');
Route::get('/fgd-cai', \App\Livewire\FgdCai::class)->name('fgd-cai');
Route::get('/fgd-cai/presentasi', \App\Livewire\FgdCaiPresentasi::class)->name('fgd-cai.presentasi');
