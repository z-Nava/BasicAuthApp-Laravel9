<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    /**
     * Servicio para autenticar a un usuario con 2FA.
    */
    public function authenticate($request)
    {
        // Buscar el usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'status' => 'error',
                'errors' => ['email' => 'Las credenciales no son válidas.']
            ];
        }

        // Verificar si el usuario tiene 2FA habilitado
        if ($user->google2fa_secret) {
            $google2fa = new Google2FA();
            if (!$google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'))) {
                return [
                    'status' => 'error',
                    'errors' => ['2fa_code' => 'El código 2FA es inválido.']
                ];
            }
        }

        // 🚀 Regenerar la sesión solo después de validar credenciales y 2FA
        session()->regenerate();

        // Iniciar sesión manualmente
        Auth::login($user);

        return ['status' => 'success'];
    }
}
