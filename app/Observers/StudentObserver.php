<?php

namespace App\Observers;

use App\Helpers\Utils;
use App\Models\Amount;
use App\Models\Receipt;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\SysRequest;

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
        $card_payment = session('card_payment', null);

        session()->forget('report');
        session()->forget('card_payment');

        $receipt_type_id = isset($card_payment)?2:1;

        $concept = 'Colegiatura '.$student->course->name;
        $report_id = null;
        $final_amount = Amount::where('crew_id', $student->crew_id)
                        ->where('course_id', $student->course_id)
                        ->where('receipt_type_id', $receipt_type_id)
                        ->first()->amount;
        $discount = null;

        if($report != null) {
            $receipt_type_id = 1;

            $amount = Amount::where('crew_id', $report->crew_id)
                    ->where('course_id', $report->course_id)
                    ->where('receipt_type_id', $receipt_type_id)
                    ->first()->amount;

            $sys_request = SysRequest::where('report_id', $report->id)->first();

            if(isset($sys_request)) {
                if($sys_request->approved) {
                    $discount = strstr($sys_request->description, "%", true);
                    $final_amount = $amount - (($discount * $amount) / 100);
                } else {
                    $final_amount = $amount;
                }
            } else {
                $final_amount = $amount;
            }

            $report_id = $report->id;

            $concept = 'Inscripción '.$report->course->name.' con descuento del '.$discount.'%';
        }

        Utils::generateReceipt(
            $student->crew_id,
            $receipt_type_id,
            $card_payment,
            $student->id,
            $report_id,
            null,
            null,
            null,
            $concept,
            $final_amount
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
