<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class InsertReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        $student = Student::find($this->input('student_id'));

        if (!$student) {
            return false;
        }

        if ($user->role_id != 1 && $user->crew_id != $student->crew_id) {
            return false;
        }

        if ($user->role_id != 1 && (int) $this->input('crew_id') !== (int) $user->crew_id) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'crew_id'              => 'required|integer|exists:crews,id',
            'student_id'           => 'required|integer|exists:students,id',
            'receipt_type_id'      => 'required|integer|exists:receipt_types,id',
            'concept'              => 'required|string|max:255',
            'amount'               => 'required|string|max:50',
            'receipt_amount'       => 'nullable|string|max:50',
            'attr_id'              => 'nullable|integer',
            'card_payment'         => 'nullable|string|in:card',
            'voucher'              => 'nullable|string|max:100',
            'bill'                 => 'nullable|string|in:bill',
            'apply_surcharge'      => 'nullable|in:1',
            'surcharge_percentage' => 'nullable|numeric|in:5,10,15,20',
            'apply_early_discount' => 'nullable|in:1',
            'early_discount_percentage' => 'nullable|integer|min:1|max:10',
        ];
    }
}
