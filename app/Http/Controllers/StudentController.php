<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\TutorRequest;
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
        if($student->first_time) {
            return view('system.students.new-profile', compact('student','schedules','payment_periodicities','modalities'));
        } else {
            return view('system.students.profile', compact('student','schedules','payment_periodicities','modalities'));
        }
    }

    public function update(Request $request)
    {
        $tutorRules = (new TutorRequest)->rules();
        $studentRules = (new StudentRequest())->rules();

        $allData = $request->all();
        $allRules = array_merge($studentRules, $tutorRules);
        $validator = Validator::make($allData, $allRules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $student = Student::find($request->student_id);
        $student->pc = $request->pc;
        $student->colony = $request->colony;
        $student->municipality = $request->minicipality;
        $student->address = $request->address;
        $student->phone = $request->phone;
        $student->cel_phone = $request->cel_phone;
        $student->email = $request->email;
        $student->schedule = $request->schedule;
        $student->sabatine = $request->sabatine;
        $student->modality_id = $request->modality_id;

        if($request->operation == "new") {
            Tutor::create([
                'student_id'=>$student->id,
                'name'=>$request->tutor_name,
                'surnames'=>$request->tutor_surnames,
                'phone'=>$request->tutor_cel_phone,
                'relationship'=>$request->tutor_relationship
            ]);
            $student->birthdate = $request->birthdate;
            $student->curp = $request->curp;
            $student->first_time = false;
        }else{
            $student->tutor->name = $request->tutor_name;
            $student->tutor->surnames = $request->tutor_surnames;
            $student->tutor->phone = $request->tutor_phone;
            $student->tutor->cel_phone = $request->tutor_cel_phone;
            $student->tutor->relationship = $request->relationship;
        }

        $student->save();

    }
}
