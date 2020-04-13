<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    //'date_format'          => 'The :attribute does not match the format :format.',
	'date_format'          => 'The :attribute does not match the format d/m/yyyy.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
		'ownershare' => [
            'totalshare' => 'Total share percentage should not exceed 100',
        ],		
		'shares' => [
            'totalshare' => 'Total owners and beneficial owners share percentage should not exceed 100',
        ],
		'signattach' => [
            'required' => 'Please upload an ID or passport copy',
			'numeric' => 'Please upload an ID or passport copy',
			'min' => 'Please upload an ID or passport copy',
        ],		
		'ownerattach.*' => [
            'required' => 'Please upload an ID or passport copy',
			'numeric' => 'Please upload an ID or passport copy',
			'min' => 'Please upload an ID or passport copy',
        ],
		'ownercount' => [
            'required' => 'Please provide at least one shareholder',
			'integer' => 'Please provide at least one shareholder',
			'min' => 'Please provide at least one shareholder',
        ],
		'beneficialshare' => [
            'totalshare' => 'Total share percentage should not exceed 100',
        ],		
		'beneficialattach.*' => [
            'required' => 'Please upload an ID or passport copy',
			'numeric' => 'Please upload an ID or passport copy',
			'min' => 'Please upload an ID or passport copy',
        ],
		'beneficialcount' => [
            'required' => 'Please provide at least one beneficial owner',
			'integer' => 'Please provide at least one beneficial owner',
			'min' => 'Please provide at least one beneficial owner',
        ],
		'directorattach.*' => [
            'required' => 'Please upload an ID or passport copy',
			'numeric' => 'Please upload an ID or passport copy',
			'min' => 'Please upload an ID or passport copy',
        ],
		'directorcount' => [
            'required' => 'Please provide at least one director',
			'integer' => 'Please provide at least one director',
			'min' => 'Please provide at least one director',
        ],
		'topproductcount' => [
            'required' => 'Please provide at least one brand',
			'integer' => 'Please provide at least one brand',
			'min' => 'Please provide at least one brand',
        ],
		'topproductsum' => [
            'required' => 'Please provide at least one brand',
			'numeric' => 'Please provide at least one brand',
			'max' => 'Total percentage of revenue should not exceed 100',
			'min' => 'Should input at least 70% of the company’s revenues by brand',
        ],
		'topcustomercount' => [
            'required' => 'Please provide at least one customer',
			'integer' => 'Please provide at least one customer',
			'min' => 'Please provide at least three buyers',
        ],
		'topsuppliercount' => [
            'required' => 'Please provide at least one supplier',
			'integer' => 'Please provide at least one supplier',
			'min' => 'Please provide at least three suppliers',
        ],
		'itemcount' => [
            'required' => 'Please provide the name of at least one brand',
			'integer' => 'Please provide the name of at least one brand',
			'min' => 'Please provide the name of at least one brand',
        ],
		'personalsignername' => [
            'required_if' => 'Please provide the name of the signee',
        ],
		'personalsigneremail' => [
            'required_if' => 'Please provide the email of the signee',
			'email' => 'Please enter a valid email address',
        ],
		'corporatesignername' => [
            'required_if' => 'Please provide the name of the signee',
        ],
		'corporatesigneremail' => [
            'required_if' => 'Please provide the name of the signee',
			'email' => 'Please enter a valid email address',
        ],
		'promissarysignername' => [
            'required_if' => 'Please provide the name of the signee',
        ],
		'promissarysigneremail' => [
            'required_if' => 'Please provide the name of the signee',
			'email' => 'Please enter a valid email address',
        ],
		'securitycheckvalue' => [
            'required_if' => 'Please provide the value of the cheque',
			'numeric' => 'Please use numeric characters for cheque value',
        ],
		'rolename' => [
            'required' => 'Please provide the role name',
			'max' => 'Please use a maximum of 60 characters',
			'unique' => 'This role name is already in use, please use a different one',
        ],
		'permissionnum' => [
            'required' => 'Each role must have at least one permission',
			'min' => 'Each role must have at least one permission',
			'numeric' => 'Each role must have at least one permission',
        ],
		'shipaddress' => [
            'required_if' => 'Please provide the address',
			'max' => 'Please use a maximum of :max characters.',
        ],
		'shippingdistrict' => [
            'required_if' => 'Please provide the district',
			'max' => 'Please use a maximum of :max characters.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
		'companyname' => 'Company name',
		'license' => 'trade license no.',
		'tax' => 'tax certificate',
		'ownername.*' => 'shareholder name',
		'owneremail.*' => 'shareholder email',
		'ownerphone.*' => 'shareholder mobile',
		'ownershare.*' => 'shareholder share',
		'beneficialname.*' => 'beneficial owner name',
		'beneficialemail.*' => 'beneficial owner email',
		'beneficialphone.*' => 'beneficial owner mobile',
		'beneficialshare.*' => 'beneficial owner share',
		'directorname.*' => 'Director name',
		'directoremail.*' => 'Director email',
		'directorphone.*' => 'Director mobile',
		'directortitle.*' => 'Director title',
		'tradefile' => 'trade license attachment',
		'assocfile' => 'articles of association attachment',
		'taxFile' => 'tax certificate attachment',
		'industries' => 'Industries operating in ',
		'incorporated' => 'Incorporation date',
		'topproductname.*' => 'Brand name',
		'topproductrevenue.*' => 'Revenue',
		'topcustomername.*' => 'Buyer name',
		'topsuppliername.*' => 'Supplier name',
		'accountname' => 'account name',
		'bankname' => 'bank name',
		'accountnumber' => 'account number',
		'signatoryname' => 'Name',
		'signatorydesignation' => 'Designation',
		'signatoryemail' => 'Email',
		'signatoryphone' => 'Phone',
		'iban' => 'IBAN',
		'routingcode' => 'routing code',
		'swift' => 'SWIFT code',
		'shippingcity' => 'city',
		'shippingcountry' => 'country',
		'deliverbydate' => 'Deliver By Date',
		'pickupbydate' => 'Pickup By Date',
		'mpn.*' => 'MPN',
		'brand.*' => 'brand',
		'quantity.*' => 'quantity',
		'price.*' => 'price',
		'buyup.*' => 'fee',
		'signername.*' => 'Signer name',
		'signeremail.*' => 'Signer email',
		'passportno.*' => 'Passport no.',
		'amount.*' => 'value',
		'slot' => 'time slot',
	],

];
