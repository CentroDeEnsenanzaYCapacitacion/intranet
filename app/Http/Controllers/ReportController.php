<?php

namespace App\Http\Controllers;

use App\Events\CreateReceiptEvent;
use App\Http\Requests\ReportRequest;
use App\Models\Course;
use App\Models\Crew;
use App\Models\Marketing;
use App\Models\Receipt;
use App\Models\Report;
use App\Models\SysRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PdfController;

class ReportController extends Controller
{
    public function getReports()
    {
        if(Auth::user()->role_id == 1) {
            $crew_reports = Report::all();
        } else {
            $crew_reports = Report::where('crew_id', Auth::user()->crew_id)->get();
        }

        return view('system.reports.show', compact('crew_reports'));
    }

    public function recipeOrRequest(Request $request)
    {
        if($request->discount == 0) {
            $report = Report::find($request->report_id);
            $amount = 1000;// TODO: obtener monto de BDD
            Receipt::create([
                'crew_id' => $report->crew_id,
                'responsible_id' => Auth::user()->id,
                'receipt_type_id' => 1,
                'payment_type_id' => $request->has('card_payment') ? 2 : 1,
                'concept' => 'Inscripción '.$report->course->name,
                'amount' => $amount,

            ]);

            return redirect()->route('system.reports.show');

        } else {
            SysRequest::create([
                'request_type_id' => 1,
                'description' => $request->discount."% - ".$request->reason,
                'user_id' => Auth::user()->id,
                'report_id' => $request->report_id
            ]);
        }
    }


    public function signDiscount($report_id)
    {
        return view('system.reports.sign_discount', compact('report_id'));
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
