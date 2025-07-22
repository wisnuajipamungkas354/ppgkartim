<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/attach/roles', function ($roleId) {
    $user = User::find(auth()->user()->id);
    $user->roles()->attach($roleId);
    $user->roles()->detach(1);

    return redirect();
});