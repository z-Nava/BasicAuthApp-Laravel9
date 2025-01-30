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
            'name_required' => 'The name is required',
            'name_invalid' => 'The name can only contain letters and spaces',
            'email_required' => 'The email is required',
            'email_invalid' => 'The email format is invalid',
            'email_unique' => 'This email is already registered',
            'email_domain_invalid' => 'The email domain is not allowed',
            'password_required' => 'Password is required',
            'password_min' => 'Your password must have at least 8 characters',
            'password_confirmed' => 'Password confirmation does not match',
            'password_complexity' => 'Your password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
            'h-captcha-response_required' => 'Please complete the captcha',
        ];               
    }
}
