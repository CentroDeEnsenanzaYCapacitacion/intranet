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

        // Si no hay report, no generar recibo automático
        if ($report == null) {
            return;
        }

        // Inscripción desde informe
        $receipt_type_id = 1;
        $report_id = $report->id;
        $discount = null;

        $amount_record = Amount::where('crew_id', $report->crew_id)
                ->where('course_id', $report->course_id)
                ->where('receipt_type_id', 1)
                ->first();

        // Manejo defensivo: Si no existe monto en amounts
        if (!$amount_record) {
            // Verificar si es BACHILLERATO EN UN EXAMEN (puede tener monto 0)
            $isBachilleratoExamen = $report->course && stripos($report->course->name, 'BACHILLERATO EN UN EXAMEN') !== false;

            if ($isBachilleratoExamen) {
                $amount = 0;
            } else {
                // No generar recibo automático si no hay monto registrado
                \Log::warning('No se generó recibo de inscripción: monto no encontrado en tabla amounts', [
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

        $sys_request = SysRequest::where('report_id', $report->id)->first();

        if ($sys_request && $sys_request->approved) {
            $discount = strstr($sys_request->description, "%", true);
            $final_amount = $amount - (($discount * $amount) / 100);
        } else {
            $final_amount = $amount;
        }

        if ($discount == null) {
            $concept = 'Inscripción '.$report->course->name;
        } else {
            $concept = 'Inscripción '.$report->course->name.' con descuento del '.$discount.'%';
        }

        Utils::generateReceipt(
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
