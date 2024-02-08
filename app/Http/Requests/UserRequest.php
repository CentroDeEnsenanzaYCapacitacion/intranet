<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required',
            'crew_id' => 'required',
            'phone' => 'required',
            'cel_phone' => 'required',
            'genre' => 'required'
        ];
    }
}
