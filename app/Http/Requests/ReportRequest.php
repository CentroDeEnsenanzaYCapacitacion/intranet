<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'name'=> 'required',
            'surnames' => 'required',
            'email' => [
                'required',
                'email'
            ],
            'marketing_id' => 'required',
            'crew_id' => 'required',
            'phone' => 'required_without:cel_phone',
            'cel_phone' => 'required_without:phone',
            'course_id' => 'required'
        ];
    }
}
