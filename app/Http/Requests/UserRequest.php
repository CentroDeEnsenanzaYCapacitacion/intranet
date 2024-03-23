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
            'name'=> 'required',
            'surnames' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->id . '|regex:/^[^@]+@[^@]+\.[^@]+$/',
            'role_id' => 'required|integer|min:0',
            'crew_id' => 'required|integer|min:0',
            'phone' => 'required_without:cel_phone|numeric|digits:10',
            'cel_phone' => 'required_without:phone|numeric|digits:10',
            'genre' => 'required'
        ];
    }
}
