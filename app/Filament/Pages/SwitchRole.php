<?php

namespace App\Filament\Pages;

use App\Models\Role;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SwitchRole extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $title = 'Pilih Peran';
    protected static ?string $routeName = 'filament.pages.switch-role';
    protected static string $view = 'filament.pages.switch-role';

    public $roles;

    public function mount(): void
    {
        $this->roles = Auth::user()->roles;
    }

    public function switchRole(int $roleId)
    {
        Session::put('active_role_id', $roleId);

        $roleName = Role::query()->where('id', $roleId)->value('name');

        Notification::make()
            ->title('Berhasil!')
            ->body('Saat ini anda berperan sebagai ' . $roleName)
            ->success()
            ->send();

        return redirect(filament()->getUrl()); // kembali ke dashboard
    }
}
