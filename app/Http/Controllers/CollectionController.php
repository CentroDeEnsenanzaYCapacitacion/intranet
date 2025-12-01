<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Models\Amount;
use App\Models\Paybill;
use App\Models\Receipt;
use App\Models\ReceiptAttribute;
use App\Models\ReceiptType;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Hash;

class CollectionController extends Controller
{
    public function getMenu()
    {
        return view('system.collection.menu');
    }

    public function showTuitions()
    {
        $students = session()->pull('searchResults', []);

        return view('system.collection.tuitions.search', compact('students'));
    }

    public function searchPost(Request $request)
    {
        $user = Auth::user();

        $students = Student::where(function ($query) use ($request) {
            $data = '%' . $request->data . '%';
            $query->where('name', 'LIKE', $data)
                  ->orWhere('surnames', 'LIKE', $data)
                  ->orWhere('id', 'LIKE', $data);
        });

        if ($user && $user->role_id != 1) {
            $students->where('crew_id', '=', $user->crew_id);
        };

        $students = $students->get();

        if ($students->isEmpty()) {
            return redirect()->route('system.collection.tuition');
        }

        session(['searchResults' => $students]);

        return view('system.collection.tuitions.search', compact('students'));

    }

    public function insertPaybill(Request $request)
    {

        Paybill::create([
            'user_id' => $request->user_id,
            'receives' => $request->receives,
            'concept' => $request->concept,
            'crew_id' => $request->crew_id,
            'amount' => $request->amount
        ]);
    }

    public function newPaybill()
    {
        $users = User::whereIn('role_id', [1, 2])->get();
        return view('system.collection.paybills.new', compact('users'));
    }

    public function insertReceipt(Request $request)
    {
        $amount = $request->amount;
        $concept = $request->concept;

        if ($request->receipt_amount != null) {
            $amount = $request->receipt_amount;
        }

        // Calcular recargo si aplica y sumar al monto
        if ($request->has('apply_surcharge') && $request->apply_surcharge == '1') {
            $surchargePercentage = $request->surcharge_percentage ?? 0;
            
            // Convertir el monto a número para calcular
            $numericAmount = is_string($amount) ? floatval(str_replace(['$', ','], '', $amount)) : (float)$amount;
            $surchargeAmount = ($numericAmount * $surchargePercentage) / 100;
            $amount = $numericAmount + $surchargeAmount;
            
            // Ajustar concepto
            $concept = rtrim($concept) . ' con recargo';
        }

        Utils::generateReceipt(
            $request->crew_id,
            $request->receipt_type_id,
            ($request->card_payment == null) ? 1 : 2,
            $request->student_id,
            $concept,
            $amount,
            null,
            $request->attr_id == 0 ? null : $request->attr_id,
            $request->voucher,
            $request->has('bill') ? true : false
        );

        return redirect()->route('algunaRuta')->with('success', 'Recibo emitido correctamente.');
    }

    public function showPaybills()
    {
        $paybills = Paybill::all();
        return view('system.collection.paybills.show', compact('paybills'));
    }

    public function getStudentTuitions($student_id)
    {
        $student = Student::find($student_id);
        $payments = Receipt::where('student_id', $student_id)->get();

        if (!isset($student->generation) || !isset($student->modality->name) || !isset($student->schedule->name)) {
            return redirect()->route('system.collection.tuition')->withInput()->withErrors(['error' => 'No se han registrado los datos del estudiante, por favor complete el expediente para poder emitir recibos de este estudiante.']);
        }
        if (!isset($student->tuition) || $student->tuition <= 0) {
            return redirect()->route('system.collection.tuition')->withInput()->withErrors(['error' => 'No se ha registrado la colegiatura del estudiante, por favor complete el expediente para poder emitir recibos.']);
        } else {
            session()->forget('searchResults');
            return view('system.collection.tuitions.show', compact('student', 'payments'));
        }
    }

    public function receiptError(Request $request)
    {
        $errorMessage = $request->input('error');
        return redirect()->back()->with('error', $errorMessage);
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

        return view('system.collection.tuitions.new', compact('student', 'course', 'crew_course_amounts', 'general_amounts', 'receipt_types', 'student_tuition_receipts', 'receipt_attributes'));
    }

    public function reprintReceipt($receipt_id)
    {
        $receipt = Receipt::findOrFail($receipt_id);
        
        // Generar QR y reimprimir recibo
        Utils::generateQR(Hash::make($receipt->id));
        PdfController::generateReceipt($receipt);
    }
}
