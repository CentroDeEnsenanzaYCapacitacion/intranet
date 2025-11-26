<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\ReportRequest;
use App\Models\Course;
use App\Models\Crew;
use App\Models\Marketing;
use App\Models\Report;
use App\Models\Amount;
use App\Models\Student;
use App\Models\SysRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function getReports()
    {
        if (Auth::user()->role_id == 1) {
            $crew_reports = Report::where('signed', false)
                                    ->get();

        } else {
            $crew_reports = Report::where('crew_id', Auth::user()->crew_id)
                                    ->where('signed', false)
                                    ->get();
        }

        $crew_requests = [];
        $idsToRemove = [];

        foreach ($crew_reports as $report) {
            $request = SysRequest::where('report_id', $report->id)->first();

            if ($request) {
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

    public function validateAmount(Request $request)
    {
        $id = $request->input('report_id');
        $type = 'report';

        $isValid = Utils::validateAmount($id, $type);

        return response()->json(['isValid' => $isValid]);
    }

    public function receiptOrRequest(Request $request)
    {
        // Obtener el reporte y verificar si es BACHILLERATO EN UN EXAMEN
        $report = Report::with('course')->find($request->report_id);
        $isBachilleratoExamen = $report && $report->course && stripos($report->course->name, 'BACHILLERATO EN UN EXAMEN') !== false;
        
        if ($request->discount == 0) {
            // Omitir validación de costo si es BACHILLERATO EN UN EXAMEN
            if (!$isBachilleratoExamen) {
                $success = Utils::validateAmount($request->report_id, "report");
                if (!$success) {
                    return back()->withErrors(['error' => 'No existe un costo resgistrado para el recibo que se intenta emitir, por favor registre un costo para continuar.']);
                }
            }
            
            // Validar duplicados en estudiantes antes de inscribir
            $duplicateStudents = Student::where(function($query) use ($report) {
                $query->where('name', $report->name)
                      ->where('surnames', $report->surnames);
            })
            ->orWhere('email', $report->email)
            ->get();

            if ($duplicateStudents->isNotEmpty()) {
                $matches = [];
                foreach ($duplicateStudents as $dup) {
                    $reasons = [];
                    if ($dup->name == $report->name && $dup->surnames == $report->surnames) {
                        $reasons[] = 'nombre completo';
                    }
                    if ($dup->email == $report->email) {
                        $reasons[] = 'email';
                    }
                    $matches[] = "Estudiante #{$dup->id}: {$dup->name} {$dup->surnames} (coincide: " . implode(', ', $reasons) . ")";
                }
                
                return back()->withErrors(['duplicado' => 'Ya existen estudiantes con datos similares:<br>' . implode('<br>', $matches)]);
            }
            
            StudentController::insertStudent($request);
        } else {
            if (!$request->reason || $request->reason == '') {
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
        session([
            'report' => $report,
            'card_payment' => ($request->card_payment == null) ? 1 : 2
        ]);

        Student::create([
            'crew_id' => $report->crew_id,
            'name' => $report->name,
            'surnames' => $report->surnames,
            'genre' => $report->genre,
            'email' => $report->email,
            'course_id' => $report->course_id
        ]);
    }


    public function signDiscount($report_id)
    {
        $request = SysRequest::where('report_id', $report_id)->first();
        if (!$request) {
            $report = Report::with('course')->findOrFail($report_id);
            return view('system.reports.sign_discount', compact('report_id', 'report'));
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
        // Buscar duplicados en informes existentes
        $duplicates = Report::where(function($query) use ($request) {
            $query->where('name', $request->name)
                  ->where('surnames', $request->surnames);
        })
        ->orWhere('email', $request->email)
        ->orWhere(function($query) use ($request) {
            $query->where('phone', $request->phone)
                  ->orWhere('cel_phone', $request->cel_phone);
        })
        ->get();

        if ($duplicates->isNotEmpty()) {
            $matches = [];
            foreach ($duplicates as $dup) {
                $reasons = [];
                if ($dup->name == $request->name && $dup->surnames == $request->surnames) {
                    $reasons[] = 'nombre completo';
                }
                if ($dup->email == $request->email) {
                    $reasons[] = 'email';
                }
                if ($dup->phone == $request->phone || $dup->cel_phone == $request->cel_phone) {
                    $reasons[] = 'teléfono';
                }
                $matches[] = "Informe #{$dup->id}: {$dup->name} {$dup->surnames} (coincide: " . implode(', ', $reasons) . ")";
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicado' => 'Se encontraron informes similares:<br>' . implode('<br>', $matches)]);
        }

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

        if ($report) {
            return redirect()->route('system.reports.show');
        } else {
            return redirect()->route('system.report.new')->with('error', 'error al guardar informe');
        }
    }
}
