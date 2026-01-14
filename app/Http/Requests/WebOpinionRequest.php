<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebOpinionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ratingValues = ['0', '0.5', '1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5'];

        return [
            'img' => ['nullable', 'array'],
            'img.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'name' => ['required', 'array'],
            'name.*' => ['required', 'string', 'max:255'],
            'course' => ['required', 'array'],
            'course.*' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'array'],
            'rating.*' => ['required', 'numeric', 'min:0', 'max:5', Rule::in($ratingValues)],
            'description' => ['required', 'array'],
            'description.*' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'img.*.image' => 'El archivo debe ser una imagen.',
            'img.*.mimes' => 'La imagen debe ser JPG o PNG.',
            'img.*.max' => 'La imagen no debe pesar mas de 2 MB.',
            'name.*.required' => 'El nombre es obligatorio.',
            'course.*.required' => 'El curso es obligatorio.',
            'rating.*.required' => 'La valoracion es obligatoria.',
            'rating.*.in' => 'La valoracion debe ser de 0 a 5 en pasos de 0.5.',
            'description.*.required' => 'La descripcion es obligatoria.',
        ];
    }
}
