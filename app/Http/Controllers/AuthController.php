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
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Controlador de autenticación de usuario
     *
     * 
     */
    protected $loginService; // Servicio de autenticación
    protected $registerService; // Servicio de registro
    protected $twoFactorService; // Servicio de autenticación de dos factores

    public function __construct(LoginService $loginService, RegisterService $registerService, TwoFactorService $twoFactorService)
    {
        // Inyectar servicios
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->twoFactorService = $twoFactorService;
    }

    // Mostrar el formulario de inicio de sesión
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function show2FAVerifyForm()
    {
        // Mostrar el formulario de verificación de 2FA
        return view('auth.2fa-verify');
    }

    // Mostrar el formulario de registro
    public function showRegisterForm(Request $request)
    {
        // Mensaje de error personalizado
        $errorMessage = $request->query('error') === '2fa_invalid'
            ? 'La confirmación de 2FA fue inválida. Por favor, regístrate nuevamente.'
            : null;
        // Mostrar el formulario de registro
        return view('auth.register', ['errorMessage' => $errorMessage]);
    }

    // Iniciar sesión
    public function login(LoginRequest $request)
    {
        // Autenticar usuario
        $result = $this->loginService->authenticate($request);

        // Verificar si la autenticación fue exitosa
        if ($result['status'] === 'success') {
            // Guardar al usuario en la sesión
            session(['user' => $result['user']]);

            // Redirigir a la vista de 2FA para ingresar el código
            return redirect()->route('auth.2fa-verify');
        }

        // Mostrar errores de autenticación
        return back()->withErrors($result['errors']);
    }

    public function verify2FA(Request $request)
    {
        $user = session('user'); // Recuperar al usuario de la sesión

        if (!$user) {
            return redirect()->route('login')->withErrors(['user' => 'Usuario no encontrado.']);
        }

        // Verificar el código de autenticación de dos factores
        $result = $this->twoFactorService->verify($user, $request);
        // Verificar si la autenticación de 2FA fue exitosa
        if ($result['status'] === 'success') {
            // Aquí es donde debes autenticar al usuario
            Auth::login($user);
            // Redirigir a la página de bienvenida
            return redirect()->route('welcome');
        }

        return back()->withErrors($result['errors']);
    }


    // Cerrar sesión
    public function logout()
    {
        // Cerrar sesión
        Auth::logout();
        // Redirigir al formulario de inicio de sesión
        return redirect()->route('login');
    }

    // Registrar usuario
    public function register(RegisterRequest $request)
    {
        // Registrar usuario
        $data = $this->registerService->registerUser($request);
        // Verificar si el registro fue exitoso
        return view('auth.2fa-setup', [
            'qrCode' => $data['qrCode'],
            'secretKey' => $data['secretKey'],
            'userId' => $data['userId'],
        ]);
    }

    // Verificar el código de autenticación de dos factores
    public function invalidate2FA(Request $request)
    {
        // Invalidar el código de autenticación de dos factores
        $this->twoFactorService->invalidate($request->input('user_id'));
        // Redirigir al formulario de registro con un mensaje de error
        return redirect()->route('register', ['error' => '2fa_invalid']);
    }
}
