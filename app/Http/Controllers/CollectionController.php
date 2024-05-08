<?php

namespace App\Http\Controllers;

use App\Models\Amount;
use App\Models\Receipt;
use App\Models\ReceiptAttribute;
use App\Models\ReceiptType;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class CollectionController extends Controller
{
    public function getMenu()
    {
        return view('system.collection.menu');
    }

    public function show()
    {
        $students = session()->pull('searchResults', []);

        return view('system.collection.tuitions.search', compact('students'));
    }

    public function searchPost(Request $request)
    {
        $students = Student::where(function ($query) use ($request) {
            $data = '%' . $request->data . '%';
            $query->where('name', 'LIKE', $data)
                  ->orWhere('surnames', 'LIKE', $data)
                  ->orWhere('id', 'LIKE', $data);
        })->get();

        if ($students->isEmpty()) {
            return redirect()->route('system.collection.tuition');
        }

        session(['searchResults' => $students]);

        return view('system.collection.tuitions.search', compact('students'));

    }

    public function receiptPost(Request $request)
    {
        dd($request);
    }

    public function getStudentTuitions($student_id)
    {
        $student = Student::find($student_id);
        $amount = Amount::where('crew_id', $student->crew_id)->where('course_id', $student->course_id)->where('receipt_type_id', 2)->first();
        $payments = Receipt::where('student_id', $student_id)->get();

        if (!isset($student->generation) || !isset($student->modality->name) || !isset($student->schedule->name)) {
            return redirect()->route('system.collection.tuition')->withInput()->withErrors(['error' => 'No se han registrado los datos del estudiante, por favor complete el expediente para poder emitir recibos de este estudiante.']);
        }
        if (!isset($amount) || $amount->amount == '0.00') {
            return redirect()->route('system.collection.tuition')->withInput()->withErrors(['error' => 'No se ha registrado el costo del curso al que pertenece este estudiante, por favor registre el costo del curso para poder emitir recibos.']);
        } else {
            session()->forget('searchResults');
            return view('system.collection.tuitions.show', compact('student', 'amount', 'payments'));
        }
    }

    public function newTuition($student_id)
    {
        $student = Student::find($student_id);
        $student_tuition_receipts = Receipt::where('student_id', $student_id)->where('receipt_type_id', 2)->orderBy('id', 'desc')->get();
        $receipt_types = ReceiptType::all();
        $course = $student->course->name;
        $crew_course_amounts = Amount::where('crew_id', $student->crew_id)->where('course_id', $student->course_id)->get();
        $general_amounts = Amount::where('crew_id', 1)->get();
        $receipt_attributes = ReceiptAttribute::all();
        $newReceiptAttribute = new ReceiptAttribute();
        $newReceiptAttribute->id = 0;
        $newReceiptAttribute->name = 'Seleccionar atributo de recibo';
        $receipt_attributes->prepend($newReceiptAttribute);

        //dd($student, $student_receipts, $receipt_types, $course, $crew_course_amounts, $general_amounts, $receipt_attributes);

        return view('system.collection.tuitions.new', compact('student', 'course', 'crew_course_amounts', 'general_amounts', 'receipt_types', 'student_tuition_receipts', 'receipt_attributes'));
    }



}
