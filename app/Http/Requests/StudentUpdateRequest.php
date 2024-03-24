<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'crew_id'=> 'required|integer|min:0',
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
