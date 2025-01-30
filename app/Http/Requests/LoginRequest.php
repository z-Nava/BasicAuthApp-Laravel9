<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    // Reglas de validación para el formulario de inicio de sesión
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',  
            '2fa_code' => 'required|digits:6',
            'h-captcha-response' => ['required'],
        ];
    }
    // Mensajes personalizados para las validaciones
    public function messages()
    {
        return [
            'email.required' => __('El email es requerido'),
            'email.email' => __('El email es invalido'),
            'email.exists' => __('El email no existe'),  
            'password.required' => __('Tu contraseña es requerida para completar el proceso'),
            'password.min' => __('Tu contraseña debe tener al menos 8 caracteres'),
            '2fa_code.required' => __('Tu codigo 2FA es requerido'),
            '2fa_code.digits' => __('Tu codigo 2FA debe tener 6 digitos'),
            'h-captcha-response_required' => __('Por favor completa el captcha'),
        ];      
    }
}
