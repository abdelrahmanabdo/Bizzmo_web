<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteAppointmentRequest extends FormRequest
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
            'prepared_by' => 'required|string|max:100',
			'approved_by' => 'required|string|max:100',
            'date_of_assessment' => 'required|date_format:j/n/Y',
            'company_background' => 'required|string',
            'key_financials_developments' => 'required|string',
            'key_risks' => 'required|string',
            'mitigating_factors' => 'required|string',
            //'companies_count' => 'required|numeric|min:3',
            'highest_outstanding_blance' => 'nullable|numeric',
            'sales_0' => 'nullable|numeric',
            'sales_1' => 'nullable|numeric',
            'sales_2' => 'nullable|numeric',
            'sales_3' => 'nullable|numeric',
            'sales_4' => 'nullable|numeric',
            'sales_5' => 'nullable|numeric',
            'sales_6' => 'nullable|numeric',
            'sales_7' => 'nullable|numeric',
            'payments_0' => 'nullable|numeric',
            'payments_1' => 'nullable|numeric',
            'payments_2' => 'nullable|numeric',
            'payments_3' => 'nullable|numeric',
            'payments_4' => 'nullable|numeric',
            'payments_5' => 'nullable|numeric',
            'payments_6' => 'nullable|numeric',
            'payments_7' => 'nullable|numeric',
            'scores_0' => 'required|numeric',
            'scores_1' => 'required|numeric',
            'scores_2' => 'required|numeric',
            'scores_3' => 'required|numeric',
            'scores_4' => 'required|numeric',
            'scores_5' => 'required|numeric',
            'scores_6' => 'required|numeric',
            'companyName.*' => 'required|string',
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
            'sales_*.required' => 'Sales field is required',
            'sales_*.numeric' => 'Sales field should be numeric',
            'payments_*.required' => 'Payments field is required',
            'payments_*.numeric' => 'Payments field should be numeric',
            'companyName.*.required' => 'Company name field is required',
        ];
    }
}
