<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\LoginService;
use App\Services\RegisterService;
use App\Services\TwoFactorService;

//composer require pragmarx/google2fa-laravel
//php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider"
//$table->string('google2fa_secret')->nullable();
//php artisan migrate:fresh
//composer require simplesoftwareio/simple-qrcode
//extension=gd

class AuthController extends Controller
{
    protected $loginService;
    protected $registerService;
    protected $twoFactorService;

    public function __construct(LoginService $loginService, RegisterService $registerService, TwoFactorService $twoFactorService)
    {
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->twoFactorService = $twoFactorService;
    }

    // === Vistas ===

    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Muestra el formulario de registro.
     */
    public function showRegisterForm(Request $request)
    {
        $errorMessage = $request->query('error') === '2fa_invalid'
            ? 'La confirmación de 2FA fue inválida. Por favor, regístrate nuevamente.'
            : null;

        return view('auth.register', ['errorMessage' => $errorMessage]);
    }

    // === Autenticación ===

    /**
     * Maneja el inicio de sesión del usuario.
     */
    public function login(LoginRequest $request)
    {
        $result = $this->loginService->authenticate($request);

        if ($result['status'] === 'success') {
            return redirect()->route('welcome');
        }

        return back()->withErrors($result['errors']);
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // === Registro ===

    /**
     * Maneja el registro de un nuevo usuario.
     */
    public function register(RegisterRequest $request)
    {
        $data = $this->registerService->registerUser($request);

        return view('auth.2fa-setup', [
            'qrCode' => $data['qrCode'],
            'secretKey' => $data['secretKey'],
            'userId' => $data['userId'],
        ]);
    }

    // === 2FA ===

    /**
     * Invalida la configuración de 2FA si no se completó.
     */
    public function invalidate2FA(Request $request)
    {
        $this->twoFactorService->invalidate($request->input('user_id'));

        return redirect()->route('register', ['error' => '2fa_invalid']);
    }
}
