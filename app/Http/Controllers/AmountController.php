<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmountRequest;
use App\Models\Amount;
use App\Models\Course;
use App\Models\Crew;
use App\Models\ReceiptType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmountController extends Controller
{
    public function __construct()
    {

        $this->middleware('role:1,2');
    }

    public function getAmounts()
    {
        $amounts = Amount::with(['crew', 'course', 'receiptType'])
            ->where(function ($query) {
                $query->whereHas('course', function ($q) {
                    $q->where('is_active', true);
                })->orWhereNull('course_id');
            })
            ->get();
        $role = $usuario = Auth::user()->role_id;
        return view('admin.catalogues.amounts.show', compact('amounts','role'));
    }

    public function updateAmount(AmountRequest $request, $id)
    {
        $amount = Amount::findOrFail($id);

        if (Auth::user()->role_id !== 1 && $amount->id <= 133) {
            abort(403);
        }

        $amount->amount = $request->amount;
        $amount->save();

        return redirect()->route('admin.catalogues.amounts.show')->with('success', 'Costo actualizado correctamente');
    }

    public function editAmount($id)
    {
        $amount = Amount::findOrFail($id);

        if (Auth::user()->role_id !== 1 && $amount->id <= 133) {
            abort(403);
        }

        return view('admin.catalogues.amounts.edit', compact('amount'));
    }

    private function searchAmount($all_amounts, $crew, $course, $type)
    {
        return $all_amounts->first(function ($amount) use ($crew, $course, $type) {
            return $amount->crew_id == $crew && $amount->course_id == $course && $amount->receipt_type_id == $type;
        });
    }

    private function createAmount(&$amounts_to_store, $crew, $course, $type)
    {
        $new_amount = new Amount();
        $new_amount->crew_id = $crew;
        $new_amount->course_id = $course;
        $new_amount->receipt_type_id = $type;
        $amounts_to_store[] = $new_amount;
    }

    public function generateAmounts()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403);
        }

        $courses = Course::where('is_active', true)->get();
        $amounts = Amount::all();

        $amounts_to_store = [];

        foreach($courses as $course) {
            $amount = $this->searchAmount($amounts, 1, $course->id, 1);
            if(!$amount) {
                $this->createAmount($amounts_to_store, 1, $course->id, 1);
            }
        }

        $amountsArray = array_map(function ($amount) {
            return [
                'crew_id' => $amount->crew_id,
                'course_id' => $amount->course_id,
                'receipt_type_id' => $amount->receipt_type_id
            ];
        }, $amounts_to_store);

        if(!empty($amountsArray)) {
            Amount::insert($amountsArray);
        }

        return redirect()->route('admin.catalogues.amounts.show');
    }

    public function cleanAmounts()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403);
        }

        Amount::where('receipt_type_id', '!=', 1)
            ->where('crew_id', '!=', 1)
            ->delete();

        return redirect()->route('admin.catalogues.amounts.show')
            ->with('success', 'Se han eliminado todos los costos que no son inscripciones');
    }

    public function createAmountForm()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403);
        }

        return view('admin.catalogues.amounts.create');
    }

    public function storeAmount(Request $request)
    {
        if (Auth::user()->role_id !== 1) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:receipt_types,name',
            'amount' => ['required', 'numeric', 'regex:/^\d{1,6}(\.\d{1,2})?$/'],
        ], [
            'name.unique' => 'Ya existe un tipo de costo con ese nombre.',
        ]);

        $receiptType = ReceiptType::create([
            'name' => $request->name,
            'automatic_amount' => false,
        ]);

        Amount::create([
            'crew_id' => 1,
            'course_id' => null,
            'receipt_type_id' => $receiptType->id,
            'amount' => $request->amount,
        ]);

        return redirect()->route('admin.catalogues.amounts.show')
            ->with('success', 'Costo creado correctamente');
    }
}
