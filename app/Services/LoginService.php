<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class LoginService
{
    public function authenticate($request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->google2fa_secret) {
                $google2fa = new Google2FA();

                if (!$google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'))) {
                    Auth::logout();

                    return [
                        'status' => 'error',
                        'errors' => ['2fa_code' => 'El código 2FA es inválido.']
                    ];
                }
            }

            session(['2fa_authenticated' => true]);
            return ['status' => 'success'];
        }

        return [
            'status' => 'error',
            'errors' => ['email' => 'Algo ha ocurrido!']
        ];
    }
}
