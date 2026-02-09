<?php

namespace App\Filament\Pages;

use App\Helpers\AccessHelper;
use App\Models\Generus;
use App\Models\Role;
use App\Traits\HandlesPermissionPage;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SwitchRole extends Page
{
    use HandlesPermissionPage;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $title = 'Pilih Peran';
    protected static ?string $routeName = 'filament.pages.switch-role';
    protected static string $view = 'filament.pages.switch-role';

    public $roles;
    public $activeRole;

    public function mount(): void
    {
        $this->roles = Auth::user()->roles;
        $this->activeRole = AccessHelper::getActiveRoleName();
    }

    public function switchRole(int $roleId)
    {
        Session::put('active_role_id', $roleId);

        $roleName = ucwords(str_replace('_', ' ', Role::query()->where('id', $roleId)->value('name')));

        Notification::make()
            ->title('Berhasil!')
            ->body('Saat ini anda berperan sebagai ' . $roleName)
            ->success()
            ->send();

        return redirect(filament()->getUrl()); // kembali ke dashboard
    }
}
