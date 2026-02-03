<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmountRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'amount' => [
                'required',
                'numeric',
                'regex:/^\d{1,6}(\.\d{1,2})?$/'
            ]
        ];

        if ($this->isMethod('post')) {
            $rules['crew_id'] = 'required|exists:crews,id';
            $rules['course_id'] = 'required|exists:courses,id';
            $rules['receipt_type_id'] = 'required|exists:receipt_types,id';
        }

        return $rules;
    }
}
