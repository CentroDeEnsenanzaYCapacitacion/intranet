<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Requests\TutorRequest;
use App\Models\Amount;
use App\Models\Modality;
use App\Models\PaymentPeriodicity;
use App\Models\Report;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Schedule;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public static function insertStudent($report_id)
    {
        $report = Report::find($report_id);
        return Student::create([
            'crew_id' => $report->crew_id,
            'name' => $report->name,
            'surnames' => $report->surnames,
            'genre' => $report->genre,
            'email' => $report->email,
            'course_id' => $report->course_id
        ]);
    }

    public function search()
    {
        $students = [];
        return view('system.students.search', compact('students'));
    }

    public function searchPost(Request $request)
    {
        $students = Student::where(function ($query) use ($request) {
            $data = '%' . $request->data . '%';
            $query->where('name', 'LIKE', $data)
                  ->orWhere('surnames', 'LIKE', $data)
                  ->orWhere('id', 'LIKE', $data);
        })->get();

        return view('system.students.search', compact('students'));
    }

    public function profile($student_id)
    {
        $student = Student::find($student_id);
        $schedules = Schedule::all();
        $payment_periodicities = PaymentPeriodicity::all();
        $modalities = Modality::all();
        $amount = Amount::where('crew_id',$student->crew_id)->where('course_id',$student->course_id)->where('receipt_type_id',2)->first();
        if($student->first_time) {
            return view('system.students.new-profile', compact('student','schedules','payment_periodicities','modalities'));
        } else {
            return view('system.students.profile', compact('student','schedules','payment_periodicities','modalities','amount'));
        }
    }

    public function update(Request $request)
    {
        if($request->operation == "new") {
            $studentRules = (new StudentRequest())->rules();
        }else{
            $studentRules = (new StudentUpdateRequest())->rules();
        }
        $tutorRules = (new TutorRequest)->rules();

        $allData = $request->all();
        $allRules = array_merge($studentRules, $tutorRules);
        $validator = Validator::make($allData, $allRules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $student = Student::find($request->student_id);
        $student->pc = $request->pc;
        $student->colony = $request->colony;
        $student->municipality = $request->municipality;
        $student->address = $request->address;
        $student->phone = $request->phone;
        $student->cel_phone = $request->cel_phone;
        $student->email = $request->email;
        $student->schedule_id = $request->schedule_id;
        $student->sabbatine = $request->sabbatine;
        $student->modality_id = $request->modality_id;

        if($request->operation == "new") {
            $student->birthdate = $request->birthdate;
            $student->curp = $request->curp;
            $student->payment_periodicity_id=$request->payment_periodicity_id;
            $student->start = $request->start;
            $student->generation = $request->gen_month.'-'.$request->gen_year;
            $student->first_time = false;

            Tutor::create([
                'student_id'=>$student->id,
                'tutor_name'=>$request->tutor_name,
                'tutor_surnames'=>$request->tutor_surnames,
                'tutor_phone'=>$request->tutor_phone,
                'tutor_cel_phone'=>$request->tutor_cel_phone,
                'relationship'=>$request->relationship
            ]);

        }else{
            $student->tutor->tutor_name = $request->tutor_name;
            $student->tutor->tutor_surnames = $request->tutor_surnames;
            $student->tutor->tutor_phone = $request->tutor_phone;
            $student->tutor->tutor_cel_phone = $request->tutor_cel_phone;
            $student->tutor->relationship = $request->relationship;
        }

        $student->save();

        if($request->operation == "new") {
            return redirect()->route('system.students.search')->with('success', 'Estudiante registrado correctamente');
        }else{
            return back()->with('success', 'Estudiante actualizado correctamente');
        }
    }
}
