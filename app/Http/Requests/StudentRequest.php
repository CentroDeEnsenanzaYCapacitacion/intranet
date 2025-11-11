<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'crew_id'=> 'required|integer|min:0',
            'curp' => 'required|regex:/^[A-Z]{4}\\d{6}[HM]{1}[A-Z]{5}[0-9A-Z]{1}\\d{1}$/|unique:students,curp',
            'name'=>'required',
            'surnames' => 'required',
            'sabbatine'=>'required',
            'genre'=>'required',
            'birthdate'=>'required',
            'start'=>'required',
            'phone' => 'nullable|required_without:cel_phone|numeric|digits:10',
            'cel_phone' => 'nullable|required_without:phone|numeric|digits:10',
            'email' => 'required|email|regex:/^[^@]+@[^@]+\.[^@]+$/',
            'course_id' => 'required|integer|min:0'
        ];
    }
}
