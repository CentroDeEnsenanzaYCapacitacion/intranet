<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TutorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'=> 'required|integer|min:0',
            'tutor_name' => 'required',
            'tutor_surnames' => 'required',
            'tutor_phone' => 'nullable|required_without:cel_phone|numeric|digits:10',
            'tutor_cel_phone' => 'nullable|required_without:phone|numeric|digits:10',
            'relationship' => 'required'
        ];
    }
}
