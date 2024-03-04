<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\Report;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Node\Block\Document;

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
        return view('system.students.profile', compact('student'));
    }
}
