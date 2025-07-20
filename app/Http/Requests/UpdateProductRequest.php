<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'name' => 'required',
            'description' => 'nullable ',
            'price' => 'required|numeric|min:0.01',
            'category' => 'required',
            'gender' => 'required',
            'discountRate' => 'required|numeric',
            'taxRate' => 'required|numeric',
            'inventoryCount' => 'integer|min:0',
            'imageUrl' => 'nullable ',
            'isActive' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute must be filled',
            'numeric' => ':attribute must be number',
            'integer' => ':attribute must be integer',
            'boolean' => ':attribute must be boolean'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'category' => 'Category',
            'gender' => 'Gender',
            'discountRate' => 'Discount rate',
            'taxRate' => 'Tax rate',
            'inventoryCount' => 'Inventory count',
            'imageUrl' => 'Image URL',
            'isActive' => 'Is active'
        ];
    }

    protected $casts = [
        'isActive' => 'boolean',
    ];
}
