<?php

namespace App\Observers;

use App\Helpers\Utils;
use App\Models\Amount;
use App\Models\Receipt;
use App\Models\Student;
use App\Models\StudentDocument;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        $documents = StudentDocument::all();
        foreach ($documents as $document) {
            $student->documents()->attach($document->id);
        }

        $report = session('report', null);
        $receipt_type_id = 2;
        $concept = 'Colegiatura '.$report->course->name;
        $report_id = null;
        if($report != null) {
            $report_id = $report->id;
            $receipt_type_id = 1;
            $concept = 'Inscripción '.$report->course->name;
        }
        $card_payment = session('card_payment', null);
        $amount = Amount::where('crew_id', $report->crew_id)
                        ->where('course_id', $report->course_id)
                        ->where('receipt_type_id', $receipt_type_id)
                        ->first();


        session()->forget('report');
        session()->forget('card_payment');

        Utils::generateReceipt(
            $student->crew_id,
            $receipt_type_id,
            $report_id,
            $student->id,
            $card_payment,
            $concept,
            $amount->amount
        );
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        //
    }
}
