<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\WebCarouselImageDimensions;

class WebCarouselRequest extends FormRequest
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
            'img_1' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', new WebCarouselImageDimensions(940, 413)],
            'img_2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', new WebCarouselImageDimensions(940, 413)],
            'img_3' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', new WebCarouselImageDimensions(940, 413)],
            'img_4' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', new WebCarouselImageDimensions(940, 413)],
            'title1' => 'nullable|string|max:255',
            'title2' => 'nullable|string|max:255',
            'title3' => 'nullable|string|max:255',
            'title4' => 'nullable|string|max:255',
            'description1' => 'nullable|string',
            'description2' => 'nullable|string',
            'description3' => 'nullable|string',
            'description4' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'img_1.image' => 'El archivo debe ser una imagen.',
            'img_1.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'img_1.dimensions' => 'La imagen debe tener las dimensiones 940x413 píxeles.',
            'img_2.image' => 'El archivo debe ser una imagen.',
            'img_2.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'img_2.dimensions' => 'La imagen debe tener las dimensiones 940x413 píxeles.',
            'img_3.image' => 'El archivo debe ser una imagen.',
            'img_3.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'img_3.dimensions' => 'La imagen debe tener las dimensiones 940x413 píxeles.',
            'img_4.image' => 'El archivo debe ser una imagen.',
            'img_4.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'img_4.dimensions' => 'La imagen debe tener las dimensiones 940x413 píxeles.',
            'title1.string' => 'El título debe ser una cadena de texto.',
            'title1.max' => 'El título no debe tener más de 255 caracteres.',
            'title2.string' => 'El título debe ser una cadena de texto.',
            'title2.max' => 'El título no debe tener más de 255 caracteres.',
            'title3.string' => 'El título debe ser una cadena de texto.',
            'title3.max' => 'El título no debe tener más de 255 caracteres.',
            'title4.string' => 'El título debe ser una cadena de texto.',
            'title4.max' => 'El título no debe tener más de 255 caracteres.',
            'description1.string' => 'La descripción debe ser una cadena de texto.',
            'description2.string' => 'La descripción debe ser una cadena de texto.',
            'description3.string' => 'La descripción debe ser una cadena de texto.',
            'description4.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }
}
