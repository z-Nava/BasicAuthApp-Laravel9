<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class LoginService
{
/**
 * Servicio para autenticar a un usuario con 2FA.
*/
    public function authenticate($request)
    {
        //Autenticar al usuario
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            //Verificar si el usuario tiene 2FA habilitado
            if ($user->google2fa_secret) {
                $google2fa = new Google2FA();
            //Verificar si el código 2FA es válido
                if (!$google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'))) {
                    Auth::logout();
                    //Devolver un error si el código 2FA es inválido
                    return [
                        'status' => 'error',
                        'errors' => ['2fa_code' => 'El código 2FA es inválido.']
                    ];
                }
            }
            //Establecer la sesión de autenticación
            session(['2fa_authenticated' => true]);
            return ['status' => 'success'];
        }

        return [
            'status' => 'error',
            'errors' => ['email' => 'Algo ha ocurrido!']
        ];
    }
}
