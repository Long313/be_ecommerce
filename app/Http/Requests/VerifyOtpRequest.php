<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'otp' => 'required|min:4|max:4',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute must be filled',
            'min' => ':attribute minimum :min characters',
            'max' => ':attribute maximum :max characters',
            'email' => ':attribute must be email format',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'Email',
            'otp' => 'OTP'
        ];
    }
}
