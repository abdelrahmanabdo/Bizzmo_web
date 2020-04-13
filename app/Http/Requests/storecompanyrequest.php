<?php

namespace App\Http\Requests;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Rule;

use Input;

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
			//$phoneRegex = "/^\+\d+(-| )?\d+(-| )?\d+(-| )?\d+(-| )?\d+$/";
			$phoneRegex = "/^[\+|\(|\)|\d|\- ]*$/";
			switch ($this->activetab) {
				case 'BasicInfo':
					return [
						'companyname' => ['required', 'max:60', Rule::unique('companies')->ignore($this->id, 'id')],
						'address' => 'required|max:180',
						'phone' => ['required', "regex:$phoneRegex"],
						'fax' => ['required', "regex:$phoneRegex"],
						'pobox' => 'max:60',
						'email' => 'required|max:60|email',
						'license' => 'required|max:60',
						'tradefile' => 'required',
						'assocfile' => 'required',
						'tax' => 'required|max:60',
						'incorporated' => 'required|date_format:j/n/Y',
						'industries' => 'required|array|min:1',
						'employees' => 'required|integer',
						'website' => 'max:60',					
					];
					break;
				case 'AuthorizedSignatory':
					return [
						'signatoryname' => 'required|max:60',
						'signatorydesignation' => 'required|max:60',
						'signatoryemail' => 'required|max:60|email',
						'signatoryphone' => ['required', "regex:$phoneRegex"],
						'signattach' => 'required|numeric|min:1',
					];
					break;
				case 'Shareholders':
					$shareholders = [
						'ownername.*' => 'required|max:60',
						'owneremail.*' => 'required|max:60|email',
						'ownerphone.*' => ['required', "regex:$phoneRegex"],
						'ownershare' => 'totalshare',
						'shares' => 'totalshare',
						'ownershare.*' => 'required|numeric|min:0|max:100',
						'ownerattach.*' => 'required|numeric|min:1',
						'ownercount' => 'required|integer|min:1',			
					];
					$shareholdersandbeneficial = [
						'ownername.*' => 'required|max:60',
						'owneremail.*' => 'required|max:60|email',
						'ownerphone.*' => ['required', "regex:$phoneRegex"],
						'ownershare' => 'totalshare',
						'shares' => 'totalshare',
						'ownershare.*' => 'required|numeric|min:0|max:100',
						'ownerattach.*' => 'required|numeric|min:1',
						'ownercount' => 'required|integer|min:1',
						'beneficialname.*' => 'required|max:60',
						'beneficialemail.*' => 'required|max:60|email',
						'beneficialphone.*' => ['required', "regex:$phoneRegex"],
						'beneficialshare' => 'totalshare',
						'beneficialshare.*' => 'required|numeric|min:0|max:100',
						'beneficialattach.*' => 'required|numeric|min:1',
						'beneficialcount' => 'required|integer|min:1',
					];
					if (Input::get('sameowner') == 1) {
						return $shareholders;
					} else {
						return $shareholdersandbeneficial;
					}				
					break;
				case 'BeneficialOwners':
					return [
						'beneficialname.*' => 'required|max:60',
						'beneficialemail.*' => 'required|max:60|email',
						'beneficialphone.*' => ['required', "regex:$phoneRegex"],
						'beneficialshare' => 'totalshare',
						'beneficialshare.*' => 'required|numeric|min:0|max:100',
						'beneficialattach.*' => 'required|numeric|min:1',
						'beneficialcount' => 'required|integer|min:1',				
					];
					break;
				case 'Directors':
					return [
						'directorname.*' => 'required|max:60',
						'directortitle.*' => 'required|max:60',
						'directoremail.*' => 'required|max:60|email',
						'directorphone.*' => ['required', "regex:$phoneRegex"],
						'directorattach.*' => 'required|numeric|min:1',
						'directorcount' => 'required|integer|min:1',			
					];
					break;
				case 'BankData':
					return [
						'accountname' => 'required|max:60',
						'bankname' => 'required|max:60',
						'accountnumber' => 'required|max:60',
						'iban' => 'required|max:60',
						'routingcode' => 'required|max:60',
						'swift' => 'required|max:60',	
					];
					break;
				case 'Business':
					if (count($this->companytype_id) > 1) {
						$this->companytype_id = '3';
					} else {
						$this->companytype_id = $this->companytype_id[0];
					}
					//dd($this->companytype_id);
					switch ($this->companytype_id) {
						case '1':
							return [
								'topproductname.*' => 'required|max:60',
								'topproductrevenue.*' => 'sometimes|nullable|numeric',
								'topproductcount' => 'required|integer|min:1',
								'topproductsum' => 'required|numeric|min:70|max:100',
								'topsuppliername.*' => 'max:60',
								'topsuppliercount' => 'required|integer|min:3',
							];
							break;
						case '2':
							return [
								'topproductname.*' => 'required|max:60',
								'topproductrevenue.*' => 'sometimes|nullable|numeric',
								'topproductcount' => 'required|integer|min:1',
								'topproductsum' => 'required|numeric|min:70|max:100',
								'topcustomername.*' => 'max:60',
								'topcustomercount' => 'required|integer|min:3',
							];						
							break;
						case '3':
							return [
								'topproductname.*' => 'required|max:60',
								'topproductrevenue.*' => 'sometimes|nullable|numeric',
								'topproductcount' => 'required|integer|min:1',
								'topproductsum' => 'required|numeric|min:70|max:100',
								'topcustomername.*' => 'max:60',
								'topcustomercount' => 'required|integer|min:3',
								'topsuppliername.*' => 'max:60',
								'topsuppliercount' => 'required|integer|min:3',
							];
							break;
						case '4':
							return [
								'topproductname.*' => 'required|max:60',
								'topproductrevenue.*' => 'sometimes|nullable|numeric',
								'topproductcount' => 'required|integer|min:1',
								'topproductsum' => 'required|numeric|min:70|max:100',
								'topcustomername.*' => 'max:60',
								'topcustomercount' => 'required|integer|min:3',
							];						
							break;
					}				
					break;
			}        
    }
}
