<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        // Reset session role setiap kali buka halaman login
        session()->forget('active_role_id');
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();
        unset($data['remember']);
        
        $auth = Filament::auth();
        $user = $auth->getProvider()->retrieveByCredentials($data);

        if (! $user || ! $auth->getProvider()->validateCredentials($user, $data)) {
            $this->addError('email', __('filament-panels::pages/auth/login.messages.failed'));
            return null;
        }
        
        $auth->login($user);

        // ✅ Simpan role ke session
        if ($user->roles()->exists()) {
            session(['active_role_id' => $user->roles->first()->id]);
        }

        // ✅ Return ke halaman Filament (dashboard)
        return app(LoginResponse::class);
    }
}
