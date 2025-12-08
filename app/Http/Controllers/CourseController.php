<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Crew;
use Illuminate\Support\Facades\Auth;


class CourseController extends Controller
{
    public function __construct()
    {
        // Proteger gestión de catálogos
        $this->middleware('role:1,2');
    }

    public function getCourses()
    {
        $courses = Course::orderBy('name', 'asc')->where('is_active', true)->get();
        $role = $usuario = Auth::user()->role_id;
        return view('admin.catalogues.courses.show', compact('courses','role'));
    }

    public function newCourse()
    {
        $crews = Crew::all();
        return view('admin.catalogues.courses.new', compact('crews'));
    }

    public function insertCourse(CourseRequest $request)
    {
        $course = Course::create([
            'name' => $request->name,
            'crew_id' => $request->crew_id
        ]);

        if($course) {
            return redirect()->route('admin.catalogues.courses.show');
        } else {
            return redirect()->route('admin.catalogues.courses.new')->with('error', 'error al guardar curso');
        }
    }

    public function editCourse($id)
    {
        $course = Course::find($id);
        $crews = Crew::all();

        return view('admin.catalogues.courses.edit', compact('course', 'crews'));
    }

    public function updateCourse(CourseRequest $request, $id)
    {
        $course = Course::find($id);
        $wasUpdated = $course ->update([
            'name' => $request->name,
            'crew_id' => $request->crew_id
        ]);

        if ($wasUpdated) {
            return redirect()->route('admin.catalogues.courses.show');
        } else {
            $crews = Crew::all();
            return redirect()->route('admin.catalogues.courses.edit', compact('course', 'crews'))->with('error', 'No se detectaron cambios en la información del curso.');
        }
    }

    public function deleteCourse($id)
    {
        $course = Course::find($id);
        $course->update([
            'is_active' => false
        ]);

        return redirect()->route('admin.catalogues.courses.show');
    }
}
