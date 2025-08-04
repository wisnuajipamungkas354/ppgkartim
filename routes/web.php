<?php

use App\Livewire\Counter;
use App\Livewire\Forms\RegistrasiGenerusForm;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/counter', Counter::class);
Route::get('/registrasi-generus-form', RegistrasiGenerusForm::class);