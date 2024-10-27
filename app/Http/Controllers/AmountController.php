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
    public function getAmounts()
    {
        $amounts = Amount::with(['crew', 'course', 'receiptType'])->get();
        $role = $usuario = Auth::user()->role_id;
        return view('admin.catalogues.amounts.show', compact('amounts','role'));
    }

    public function updateAmount(AmountRequest $request, $id)
    {
        $amount = Amount::find($id);
        $amount->amount = $request->amount;

        $amount->save();

        return redirect()->route('admin.catalogues.amounts.show')->with('success', 'Costo actualizado correctamente');

    }

    public function editAmount($id)
    {
        $amount = Amount::find($id);

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
        $automatic_types = ReceiptType::where('automatic_amount', true)->get();
        $no_automatic_types = ReceiptType::where('automatic_amount', false)->get();
        $courses = Course::all();
        $crews = Crew::all();
        $amounts = Amount::all();

        $amounts_to_store = [];

        foreach($courses as $course) {
            if($course->crew->name == "Todos") {
                foreach($crews as $crew) {
                    if($crew->id > 1) {
                        foreach($automatic_types as $type) {
                            $amount = $this->searchAmount($amounts, $crew->id, $course->id, $type->id);
                            if(!$amount) {
                                $this->createAmount($amounts_to_store, $crew->id, $course->id, $type->id);
                            }
                        }
                    }
                }
            } else {
                foreach($automatic_types as $type) {
                    $amount = $this->searchAmount($amounts, $course->crew_id, $course->id, $type->id);
                    if(!$amount) {
                        $this->createAmount($amounts_to_store, $course->crew_id, $course->id, $type->id);
                    }
                }
            }
        }

        foreach($no_automatic_types as $type) {
            $amount = $this->searchAmount($amounts, 1, null, $type->id);
            if(!$amount) {
                $this->createAmount($amounts_to_store, 1, null, $type->id);
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
            $amounts = Amount::all();
        }

        return view('admin.catalogues.amounts.show', compact('amounts'));
    }
}
