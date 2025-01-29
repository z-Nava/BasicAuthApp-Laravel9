<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegisterService
{
    public function registerUser($request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();

        $user->google2fa_secret = $secretKey;
        $user->save();

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'BasicAuthApp',
            $user->email,
            $secretKey
        );

        $qrCode = QrCode::size(200)->generate($qrCodeUrl);

        return [
            'qrCode' => $qrCode,
            'secretKey' => $secretKey,
            'userId' => $user->id,
        ];
    }
}
