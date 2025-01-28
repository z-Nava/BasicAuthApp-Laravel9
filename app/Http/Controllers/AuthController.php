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

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->google2fa_secret) {
                $google2fa = new Google2FA();

                if (!$google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'))) {
                    Auth::logout(); // Cerrar sesión si falla el 2FA
                    return back()->withErrors(['2fa_code' => 'El código 2FA es inválido.']);
                }
            }

            session(['2fa_authenticated' => true]);
            return redirect()->route('welcome'); // Usar nombre de la ruta
        }

        return back()->withErrors(['email' => 'Algo ha ocurrido!']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login'); // Usar nombre de la ruta
    }

    public function showRegisterForm(Request $request)
    {
        $errorMessage = $request->query('error') === '2fa_invalid' 
            ? 'La confirmación de 2FA fue inválida. Por favor, regístrate nuevamente.' 
            : null;

        return view('auth.register', ['errorMessage' => $errorMessage]);
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
            'BasicAuthApp',
            $user->email,
            $secretKey
        );

        $qrCode = QrCode::size(200)->generate($qrCodeUrl);

        return view('auth.2fa-setup', [
            'qrCode' => $qrCode,
            'secretKey' => $secretKey,
            'userId' => $user->id,
        ]);
    }

    public function invalidate2FA(Request $request)
    {
        $user = User::find($request->input('user_id'));

        if ($user && !$user->google2fa_verified) {
            $user->delete();
        }

        return redirect()->route('register', ['error' => '2fa_invalid']); // Usar nombre de la ruta
    }
}
