<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;

class TwoFactorService
{
/**
 * Servicio para invalidar a un usuario con 2FA.
*/
    public function invalidate($userId)
    {
        //Buscar al usuario
        $user = User::find($userId);
        //Eliminar al usuario si no está verificado
        if ($user && !$user->google2fa_verified) {
            //Eliminar al usuario
            $user->delete();
        }
    }

    /**
     * Servicio para verificar a un usuario con 2FA.
     */
    public function verify($user, $request)
    {
        // Verificar si el usuario está presente
        if (!$user) {
            return [
                'status' => 'error',
                'errors' => ['user' => 'Usuario no encontrado.']
            ];
        }
    
        // Verificar si el usuario tiene 2FA habilitado
        if ($user->google2fa_secret) {
            $google2fa = new Google2FA();
            // Verificar el código 2FA con el secreto de Google 2FA
            if (!$google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'))) {
                return [
                    'status' => 'error',
                    'errors' => ['2fa_code' => 'El código 2FA es inválido.']
                ];
            }
    
            return ['status' => 'success'];
        } else {
            return [
                'status' => 'error',
                'errors' => ['2fa_code' => '2FA no está habilitado para este usuario.']
            ];
        }
    }
    
}