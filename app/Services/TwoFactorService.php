<?php

namespace App\Services;

use App\Models\User;

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
}
