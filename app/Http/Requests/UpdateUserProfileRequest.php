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
            'gender' => 'required|string|max:10',
            'birthday' => 'nullable|date',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'max' => ':attribute maximum :max characters',
        ];
    }

    public function attributes()
    {
        return [
            'fullname' => 'Fullname',
            'gender' => 'Gender',
            'birthday' => 'Birthday',
            'address' => 'Address',
            'avatar' => 'Avatar',
        ];
    }
}
