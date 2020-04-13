<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
			 'product_name' => 'required',
			 'product_description' => 'required',
             'product_category' => 'required',
             'product_currency' => 'required',
			 'product_price' => 'required|numeric',
             'images_count' => 'not_in:0',			
             
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_name.required' => 'Please provide product name',
			'product_description.required' => 'Please provide description',
			'product_currency.required' => 'Currency is required',
            'product_category.required' => 'Please select a category',
			'product_price.required' => 'Please provide a price',
			'product_price.numeric' => "Price must be numeric",
			'images_count.not_in:0' => 'Please provide at least one product image',
        ];
    }
}
