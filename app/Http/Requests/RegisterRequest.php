<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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

     // Reglas de validación para el formulario de registro
    public function rules()
    {
        return [
            'name' => 'required|string|max:50|regex:/^[a-zA-Z\s]+$/',  
            'email' => 'required|email|unique:users,email|regex:/^((?!example.com).)*$/', 
            'password' => 'required|min:8|confirmed|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[\W_]/',  
            'h-captcha-response' => ['required'],
        ];
    }
    // Mensajes personalizados para las validaciones del formulario de registro
    public function messages()
    {
        return [
            'name_required' => 'El nombre es requerido',
            'name_invalid' => 'El nombre solo puede contener letras y espacios',
            'email_required' => 'El correo es requerido',
            'email_invalid' => 'El formato del correo es invalido',
            'email_unique' => 'Este correo ya fue registrado',
            'email_domain_invalid' => 'El dominio del correo es invalido',
            'password_required' => 'La contraseña es requerida',
            'password_min' => 'Tu contraseña debe tener al menos 8 caracteres',
            'password_confirmed' => 'Tu confirmacion de contraseña no coincide',
            'password_complexity' => 'Tu contraseña debe contener una letra mayuscula, una letra miniscula, un numero y un caracter especial.',
            'h-captcha-response_required' => 'Por favor completa el captcha',
        ];               
    }
}
