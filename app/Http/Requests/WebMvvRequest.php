<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebMvvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description1' => 'nullable|string|max:355',
            'description2' => 'nullable|string|max:355',
            'description3' => 'nullable|string|max:355',
            'description4' => 'nullable|string|max:355',
        ];
    }

    public function messages()
    {
        return [
            'description1.max' => 'La descripción 1 no puede exceder los 355 caracteres.',
            'description2.max' => 'La descripción 2 no puede exceder los 355 caracteres.',
            'description3.max' => 'La descripción 3 no puede exceder los 355 caracteres.',
            'description4.max' => 'La descripción 4 no puede exceder los 355 caracteres.',
        ];
    }
}
