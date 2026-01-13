<?php

namespace App\Observers;

use App\Helpers\Utils;
use App\Models\Amount;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\SysRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StudentObserver
{
    public function creating(Student $student): void
    {
        // Generate password if not set
        if (empty($student->password)) {
            $plainPassword = Str::random(8);
            $student->password = Hash::make($plainPassword);

            // Store plain password in session temporarily
            session(['temp_student_password' => $plainPassword]);
        }
    }

    public function created(Student $student): void
    {
        // If password was generated, store it in session with student ID
        if (session()->has('temp_student_password')) {
            $plainPassword = session('temp_student_password');
            session(['student_plain_password_' . $student->id => $plainPassword]);
            session()->forget('temp_student_password');
        }

        $documents = StudentDocument::all();
        foreach ($documents as $document) {
            $student->documents()->attach($document->id);
        }

        /** @var \App\Models\Report|null $report */
        $report = session('report', null);
        $card_payment = session('card_payment', 1);
        $inscription_amount = session('inscription_amount', null);

        session()->forget('report');
        session()->forget('card_payment');
        session()->forget('inscription_amount');

        if ($report == null) {
            return;
        }

        if ($card_payment === null || !in_array($card_payment, [1, 2])) {
            $card_payment = 1;
        }

        $receipt_type_id = 1;
        $report_id = $report->id;
        $discount = null;

        if ($inscription_amount !== null) {
            $amount = $inscription_amount;
        } else {
            $amount_record = Amount::where('crew_id', 1)
                    ->where('course_id', $report->course_id)
                    ->where('receipt_type_id', 1)
                    ->first();

            if (!$amount_record) {

                $isBachilleratoExamen = $report->course && stripos($report->course->name, 'BACHILLERATO EN UN EXAMEN') !== false;

                if ($isBachilleratoExamen) {
                    $amount = 0;
                } else {

                    Log::warning('No se generó recibo de inscripción: monto no encontrado en tabla amounts', [
                        'report_id' => $report->id,
                        'crew_id' => $report->crew_id,
                        'course_id' => $report->course_id,
                        'student_id' => $student->id
                    ]);
                    return;
                }
            } else {
                $amount = $amount_record->amount;
            }
        }

        $final_amount = $amount;
        $concept = 'Inscripción '.$report->course->name;

        $receipt = Utils::generateReceipt(
            $student->crew_id,
            $receipt_type_id,
            $card_payment,
            $student->id,
            $concept,
            $final_amount,
            $report_id,
            null,
            null,
            null
        );

        session(['created_receipt_id' => $receipt->id]);
    }

    public function updated(Student $student): void
    {

    }

    public function deleted(Student $student): void
    {

    }

    public function restored(Student $student): void
    {

    }

    public function forceDeleted(Student $student): void
    {

    }
}
