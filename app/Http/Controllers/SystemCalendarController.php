<?php

namespace App\Http\Controllers;

use App\Models\StudentExamWindow;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemCalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:1,2');
    }

    public function eub()
    {
        $user = Auth::user();

        $students = Student::with(['crew', 'course', 'eubExamWindow'])
            ->whereHas('course', function ($query) {
                $query->where('name', 'like', '%BACHILLERATO EN UN EXAMEN%');
            })
            ->orderBy('surnames')
            ->orderBy('name');

        if ($user && $user->role_id != 1) {
            $students->where('crew_id', '=', $user->crew_id);
        }

        $students = $students->get();

        return view('system.calendar.eub', compact('students'));
    }

    public function updateEub(Request $request, $studentId)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'start_at' => 'nullable|date_format:Y-m-d\\TH:i|required_with:end_at',
            'end_at' => 'nullable|date_format:Y-m-d\\TH:i|after:start_at|required_with:start_at',
        ]);

        $studentQuery = Student::where('id', $studentId)
            ->whereHas('course', function ($query) {
                $query->where('name', 'like', '%BACHILLERATO EN UN EXAMEN%');
            });

        if ($user && $user->role_id != 1) {
            $studentQuery->where('crew_id', '=', $user->crew_id);
        }

        $student = $studentQuery->firstOrFail();

        $startAt = $validated['start_at'] ?? null;
        $endAt = $validated['end_at'] ?? null;

        if (!$startAt && !$endAt) {
            StudentExamWindow::where('student_id', $student->id)
                ->where('exam_key', 'eub')
                ->delete();

            return back()->with('success', 'Horario eliminado correctamente.');
        }

        StudentExamWindow::updateOrCreate(
            [
                'student_id' => $student->id,
                'exam_key' => 'eub',
            ],
            [
                'start_at' => $startAt,
                'end_at' => $endAt,
            ]
        );

        return back()->with('success', 'Horario guardado correctamente.');
    }
}
