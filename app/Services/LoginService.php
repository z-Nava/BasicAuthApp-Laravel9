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

        return ['status' => 'success', 'user' => $user];
    }
}
