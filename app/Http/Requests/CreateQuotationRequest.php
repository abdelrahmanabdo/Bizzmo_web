<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuotationRequest extends FormRequest
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
            'date' => 'required|date_format:j/n/Y',
			'deliverbydate' => 'required|date_format:j/n/Y',
			'pickupbydate' => 'required|date_format:j/n/Y',
			'shippingaddress_id' => 'required|integer|min:1',
			'pickupaddress_id' => 'required|integer|min:1',			
			'note' => 'max:180',
			'itemcount' => 'required|integer|min:1',
			'productname.*' => 'required_with:mpn.*,brand.*,price.*,quantity.*|max:180',
			'mpn.*' => 'required_with:productname.*,brand.*,price.*,quantity.*|max:60',
			'brand.*' => 'required_with:productname.*,mpn.*,price.*,quantity.*|max:60',
			'price.*' => 'sometimes|nullable|required_with:productname.*,mpn.*,brand.*,quantity.*|numeric',
			'quantity.*' => 'sometimes|nullable|required_with:productname.*,mpn.*,brand.*,price.*|numeric',
			'company_id' => 'required'
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
            'otherCountry.required_if' => 'Please provide the shipping country',
			'otherCity.required_if' => 'Please provide the shipping city',
			'company_id.required' => 'Please provide a buyer',
			'note' => "Note length should not exceed 180 characters",
			'shippingaddress_id.required' => 'Please provide a shipping address',
			'productname.*.max' => "Product description length should not exceed 180 characters",
			'productname.*.required_with' => "Please provide the product description",
			'mpn.*' => "Please provide the MPN",
			'brand.*' => "Please select the brand",
			'price.*' => "Please provide the price",
			'quantity.*' => "Please provide the quantity",
        ];
    }
}
