<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'username' => 'required|min:4|unique:users',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|max:20|unique:users',
            'fullname' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute must be filled',
            'unique' => ':attribute is duplicated',
            'min' => ':attribute minimum :min characters',
            'max' => ':attribute maximum :max characters',
            'email' => ':attribute must be email format',
        ];
    }

    public function attributes()
    {
        return [
            'fullname' => 'Fullname',
            'email' => 'Email',
            'phone_number' => 'Phone number',
            'username' => 'Username',
            'password' => 'Password'
        ];
    }
}
