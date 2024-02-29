<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Course;
use App\Models\Crew;
use App\Models\Marketing;
use App\Models\Receipt;
use App\Models\Report;
use App\Models\SysRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function getReports()
    {
        if(Auth::user()->role_id == 1) {
            $crew_reports = Report::where('signed', false)
                                    ->get();

        } else {
            $crew_reports = Report::where('crew_id', Auth::user()->crew_id)
                                    ->where('signed', false)
                                    ->get();
        }

        $crew_requests = [];
        $idsToRemove = [];

        foreach($crew_reports as $report) {
            $request = SysRequest::where('report_id', $report->id)->first();

            if($request) {
                $array = explode('-', $request->description);
                $report->status = is_null($request->approved) ? "Pendiente" : ($request->approved ? "Aprobado" : "Rechazado");
                $report->request_date = $request->created_at;
                $report->request_id = $request->id;
                $report->discount = trim($array[0]);
                $crew_requests[] = $report;
                $idsToRemove[] = $report->id;
            }
        }

        $crew_reports = $crew_reports->reject(function ($report) use ($idsToRemove) {
            return in_array($report->id, $idsToRemove);
        });


        return view('system.reports.show', compact('crew_reports', 'crew_requests'));
    }

    public static function updateReport($report_id)
    {

        $report = Report::find($report_id);

        $report->signed = true;

        $report->save();

    }

    public function receiptOrRequest(Request $request)
    {
        if($request->discount == 0) {
            $report = Report::find($request->report_id);
            $amount = 1000;// TODO: obtener monto de BDD
            Receipt::create([
                'crew_id' => $report->crew_id,
                'user_id' => Auth::user()->id,
                'receipt_type_id' => 1,
                'report_id' => $report->id,
                'payment_type_id' => $request->has('card_payment') ? 2 : 1,
                'concept' => 'Inscripción '.$report->course->name,
                'amount' => $amount,

            ]);

            return redirect()->route('system.reports.show');

        } else {
            if(!$request->reason || $request->reason == '') {
                return redirect()->route('system.reports.signdiscount', ['report_id' => $request->report_id])
                                 ->with('error', 'La razón de la solicitud debe proporcionarse')
                                 ->with('selection', $request->discount);
            }
            SysRequest::create([
                'request_type_id' => 1,
                'description' => $request->discount."% - ".$request->reason,
                'user_id' => Auth::user()->id,
                'report_id' => $request->report_id
            ]);

            return redirect()->route('system.reports.show')->with('success', 'Solicitud enviada correctamente');
        }
    }

    public function receiptConfirmation($report_id)
    {
        $request = SysRequest::where('report_id', $report_id)->first();
        return view('system.reports.receipt', compact('request'));
    }

    public function generateReceipt(Request $request)
    {
        $report = Report::find($request->report_id);
        $amount = 1000;// TODO: obtener monto de BDD
        Receipt::create([
            'crew_id' => $report->crew_id,
            'user_id' => Auth::user()->id,
            'receipt_type_id' => 1,
            'report_id' => $report->id,
            'payment_type_id' => $request->has('card_payment') ? 2 : 1,
            'concept' => 'Inscripción '.$report->course->name,
            'amount' => $amount,

        ]);

        return redirect()->route('system.reports.show');
    }


    public function signDiscount($report_id)
    {
        $request = SysRequest::where('report_id', $report_id)->first();
        if(!$request) {
            return view('system.reports.sign_discount', compact('report_id'));
        } else {
            return redirect()->route('system.reports.show')->with('error', 'Este informe ya tiene una solicitud');
        }
    }

    public function newReport()
    {

        $courses = Course::all();
        $marketings = Marketing::all();
        $crews = Crew::all();
        return view('system.reports.new', compact('courses', 'marketings', 'crews'));
    }

    public function insertReport(ReportRequest $request)
    {
        $report = Report::create([
            'name' => $request->name,
            'surnames' => $request->surnames,
            'email' => $request->email,
            'marketing_id' => $request->marketing_id,
            'crew_id' => $request->crew_id,
            'phone' => $request->phone,
            'genre' => $request->genre,
            'cel_phone' => $request->cel_phone,
            'course_id' => $request->course_id,
            'responsible_id' => Auth::id()
        ]);

        if($report) {
            return redirect()->route('system.reports.show');
        } else {
            return redirect()->route('system.report.new')->with('error', 'error al guardar informe');
        }
    }
}
