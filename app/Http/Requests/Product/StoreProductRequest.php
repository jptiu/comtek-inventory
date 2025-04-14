<?php

namespace App\Http\Requests\Product;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_image'     => 'image|file|max:2048',
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|integer|exists:categories,id',
            'unit_id'           => 'required|integer|exists:units,id',
            'quantity'          => 'integer|min:0',
            'buying_price'      => 'required|numeric|min:0',
            'selling_price'     => 'required|numeric|min:0',
            'quantity_alert'    => 'integer|min:0',
            'tax'               => 'nullable|numeric|min:0',
            'tax_type'          => 'nullable|string|in:percentage,amount',
            'notes'             => 'nullable|string|max:1000',
            // 'product_codes.*.code' => 'string|max:255|unique:product_codes,code',
            // 'product_codes.*.type' => 'string|in:barcode,sku,other'
        ];
    }

    // protected function prepareForValidation(): void
    // {
    //     $this->merge([
    //         'slug' => Str::slug($this->name, '-'),
    //         'code' =>
    //     ]);
    // }
}
