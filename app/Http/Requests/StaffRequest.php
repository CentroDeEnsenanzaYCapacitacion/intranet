<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'Address' => 'nullable|string|max:255',
            'colony' => 'nullable|string|max:255',
            'municipalty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'cel' => 'nullable|string|max:20',
            'rfc' => 'nullable|string|max:20',
            'personal_mail' => 'nullable|email|max:255',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'departments' => 'nullable|array',
            'departments.*.department_id' => 'nullable|exists:departments,id',
            'departments.*.cost' => 'nullable|numeric|min:0',
            'departments.*.cost_type' => 'nullable|in:day,hour',
        ];

        if ($this->isMethod('post')) {
            $rules['name'] = 'required|string|max:255';
            $rules['surnames'] = 'nullable|string|max:255';
            $rules['requiresMail'] = 'sometimes|boolean';
        }

        if ($this->isMethod('put')) {
            $rules['name'] = 'sometimes|string|max:255';
            $rules['surnames'] = 'sometimes|string|max:255';
        }

        return $rules;
    }
}
