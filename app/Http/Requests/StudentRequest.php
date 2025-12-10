<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {

        if ($this->has('curp')) {
            $this->merge([
                'curp' => trim(strtoupper($this->curp))
            ]);
        }
    }

    public function rules(): array
    {

        $studentId = $this->input('student_id');

        return [
            'crew_id'=> 'required|integer|min:0',
            'curp' => [
                'required',
                'string',
                'size:18',
                'regex:/^[A-Z]{4}\d{6}[HM][A-Z]{2}[BCDFGHJKLMNPQRSTVWXYZ]{3}[A-Z0-9]{2}$/',
                'unique:students,curp' . ($studentId ? ',' . $studentId : '')
            ],
            'name'=>'required',
            'surnames' => 'required',
            'sabbatine'=>'required',
            'genre'=>'required',
            'birthdate'=>'required',
            'start'=>'required',
            'phone' => 'nullable|required_without:cel_phone|numeric|digits:10',
            'cel_phone' => 'nullable|required_without:phone|numeric|digits:10',
            'email' => 'required|email|regex:/^[^@]+@[^@]+\.[^@]+$/',
            'course_id' => 'required|integer|min:0',
            'tuition' => 'required|numeric|min:0.01|decimal:0,2'
        ];
    }
}
