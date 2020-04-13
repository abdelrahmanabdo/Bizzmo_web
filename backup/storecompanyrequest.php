<?php

namespace App\Http\Requests;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class storecompanyrequest extends FormRequest
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

	public function validator(Factory $factory)
	{
		if ($this->input('ownershare') && $this->input('beneficialshare')) {
			$this->merge([
				'shares' => array_merge($this->input('ownershare'),$this->input('beneficialshare')) 
			]);
		} else {
			$this->merge([
				'shares' => array() 
			]);
			}
		return $factory->make(
			$this->all(),
			$this->rules(),
			$this->messages()
		);
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		//echo $this->query('id');
		//echo $this->id;
        return [
            'companyname' => ['required', 'max:60', Rule::unique('companies')->ignore($this->id, 'id')],
			'address' => 'required|max:60',
			'district' => 'required|max:60',
			'phone' => 'required|max:60',
			'fax' => 'required|max:60',
			'pobox' => 'max:60',
			'email' => 'required|max:60|email',
			'license' => 'required|max:60',
			'tradefile' => 'required',
			'assocfile' => 'required',
			'tax' => 'required|max:60',
			'incorporated' => 'required|date_format:j/n/Y',
			'operating' => 'required|max:120',
			'employees' => 'required|integer',
			'website' => 'max:60',
			'ownername.*' => 'required|max:60',
			'owneremail.*' => 'required|max:60|email',
			'ownerphone.*' => 'required|max:60',
			'ownershare' => 'totalshare',
			'shares' => 'totalshare',
			'ownershare.*' => 'required|numeric|min:0|max:100',
			'ownerattach.*' => 'required|numeric|min:1',
			'ownercount' => 'required|integer|min:1',
			'beneficialname.*' => 'required|max:60',
			'beneficialemail.*' => 'required|max:60|email',
			'beneficialphone.*' => 'required|max:60',
			'beneficialshare' => 'totalshare',
			'beneficialshare.*' => 'required|numeric|min:0|max:100',
			'beneficialattach.*' => 'required|numeric|min:1',
			'beneficialcount' => 'required|integer|min:1',
			'directorname.*' => 'required|max:60',
			'directortitle.*' => 'required|max:60',
			'directoremail.*' => 'required|max:60|email',
			'directorphone.*' => 'required|max:60',
			'directorattach.*' => 'required|numeric|min:1',
			'directorcount' => 'required|integer|min:1',
			'topproductname.*' => 'required|max:60',
			'topproductrevenue.*' => 'required|numeric',
			'topproductcount' => 'required|integer|min:1',
			'topproductsum' => 'required|numeric|min:70|max:100',
			'topcustomername.*' => 'required|max:60',
			'topsuppliercount' => 'required|integer|min:1',
        ];
    }
}
