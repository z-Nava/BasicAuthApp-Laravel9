<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

//composer require pragmarx/google2fa-laravel
//php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider"
//$table->string('google2fa_secret')->nullable();
//php artisan migrate:fresh
//composer require simplesoftwareio/simple-qrcode
//extension=gd


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            '2fa_code' => 'required|digits:6',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();
            
            if ($user->google2fa_secret) {
                $google2fa = new Google2FA();

            // Validar el código 2FA
            if (!$google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'))) {
                Auth::logout(); // Cerrar sesión si falla el 2FA
                return back()->withErrors(['2fa_code' => 'El código 2FA es inválido.']);
            }
        }

       
        session(['2fa_authenticated' => true]); 
        return redirect()->intended('welcome');
            return redirect('welcome');
        }

        return back()->withErrors(['email' => 'Algo a ocurrido!']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

      
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
            'BasicAuthApp',    // Nombre de tu aplicación
            $user->email,     // Identificador único
            $secretKey        // Clave secreta generada
        );

        // Generar el código QR
        $qrCode = QrCode::size(200)->generate($qrCodeUrl);

        return view('auth.2fa-setup', [
            'qrCode' => $qrCode,
            'secretKey' => $secretKey,
        ]);
    }
}
