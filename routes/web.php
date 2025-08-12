<?php

use App\Livewire\Counter;
use App\Livewire\Event\EventPresensi;
use App\Livewire\Event\PilihEvent;
use App\Livewire\Forms\RegistrasiGenerusForm;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/counter', Counter::class);
Route::get('/registrasi-generus-form', RegistrasiGenerusForm::class);
Route::get('/events', PilihEvent::class)->name('events');
Route::get('/event/{event}/presensi', EventPresensi::class)->name('events.presensi');