<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;


class TwoFactorService
{
    private $google2fa;

    // Inyectamos el servicio de Google2FA en el constructor
    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
    }

    /**
     * Servicio para invalidar a un usuario con 2FA.
     */
    public function invalidate($userId)
    {
        // Buscar al usuario
        $user = User::find($userId);
        
        // Eliminar al usuario si no está verificado
        if ($user && !$user->google2fa_verified) {
            // Eliminar al usuario
            $user->delete();
        }
    }

    /**
     * Servicio para verificar a un usuario con 2FA.
     */
    public function verify($user, $request)
    {
        // Verificar si el usuario es nulo
        if (!$user) {
            return [
                'status' => 'error',
                'errors' => ['user' => 'Usuario no encontrado.']
            ];
        }

        // Verificar si el usuario tiene un secreto 2FA
        if (!$user->google2fa_secret) {
            return [
                'status' => 'error',
                'errors' => ['2fa_code' => 'El usuario no tiene 2FA habilitado.']
            ];
        }

        // Verificar el código de autenticación de dos factores
        if (!$this->google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'))) {
            return [
                'status' => 'error',
                'errors' => ['2fa_code' => 'El código 2FA es inválido.']
            ];
        }

        return ['status' => 'success'];
    }
    
}