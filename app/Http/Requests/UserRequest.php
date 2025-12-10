<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'surnames' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->id . '|ends_with:@capacitacioncec.edu.mx',
            'role_id' => 'required|integer|min:0',
            'crew_id' => 'required_unless:role_id,1,5|integer|min:1|exists:crews,id',
            'phone' => 'required_without:cel_phone|numeric|digits:10',
            'cel_phone' => 'required_without:phone|numeric|digits:10',
            'genre' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'email.ends_with' => 'El correo electrónico debe ser del dominio @capacitacioncec.edu.mx',
        ];
    }
}
