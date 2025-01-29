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
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',  
            '2fa_code' => 'required|digits:6',
            'h-captcha-response' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('The email is required'),
            'email.email' => __('The email is invalid'),
            'email.exists' => __('The email is not found'),  
            'password.required' => __('Password is required to complete the process'),
            'password.min' => __('Your password must have at least 8 characters'),
            '2fa_code.required' => __('The 2FA code are required'),
            '2fa_code.digits' => __('The 2FA code must have at least 6 digits'),
            'h-captcha-response_required' => __('Please complete the captcha'),
        ];      
    }
}
