<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegisterService
{
/**
 * Servicio para registrar a un usuario con 2FA.
*/
    public function registerUser($request)
    {
    //Crear un nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    //Generar una clave secreta para el usuario
        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();
    //Guardar la clave secreta en la base de datos
        $user->google2fa_secret = $secretKey;
        $user->save();
    //Generar un código QR para el usuario
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'BasicAuthApp',
            $user->email,
            $secretKey
        );
    //Generar el código QR
        $qrCode = QrCode::size(200)->generate($qrCodeUrl);

        return [
            'qrCode' => $qrCode,
            'secretKey' => $secretKey,
            'userId' => $user->id,
        ];
    }
}
