<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'fullname' => 'required|string|max:100',
            'phoneNumber' => 'required|max:20',
            'gender' => 'required|string|max:10',
            'birthday' => 'nullable|date',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute must be filled',
            'max' => ':attribute maximum :max characters',
            'string' => ':attribute must be string',
            'date' => ':attribute must be date format',
            'image' => ':attribute must be image format'
        ];
    }

    public function attributes()
    {
        return [
            'fullname' => 'Fullname',
            'phoneNumber' => 'Phone number',
            'gender' => 'Gender',
            'birthday' => 'Birthday',
            'address' => 'Address',
            'avatar' => 'Avatar',
        ];
    }
}
