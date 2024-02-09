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
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->id),
            ],
            'role_id' => 'required',
            'crew_id' => 'required',
            'phone' => 'required_without:cel_phone',
            'cel_phone' => 'required_without:phone',
            'genre' => 'required'
        ];
    }
}
