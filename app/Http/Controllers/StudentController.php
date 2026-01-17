<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Requests\TutorRequest;
use App\Models\Modality;
use App\Models\Observation;
use App\Models\SysRequest;
use App\Models\PaymentPeriodicity;
use App\Models\Report;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Schedule;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Mail\StudentWelcome;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function __construct()
    {

        $this->middleware('role:1,2,3');
    }

    public static function insertStudent($request)
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
            'phone' => $report->phone,
            'cel_phone' => $report->cel_phone,
            'course_id' => $report->course_id
        ]);
    }

    public function saveFormData($student_id, Request $request)
    {

        session(['student_form_data_' . $student_id => $request->all()]);
        return response()->json(['success' => true]);
    }

    public function profile_image($student_id)
    {
        return view('system.students.profile-image', compact('student_id'));
    }

    public function upload_profile_image($student_id, Request $request)
    {
        $student = Student::find($student_id);

        if (!$student) {
            abort(404);
        }

        $user = Auth::user();
        if ($user->role_id != 1 && $user->crew_id != $student->crew_id) {
            abort(403);
        }

        $messages = [
            'image.required' => 'Es necesario que seleccione un archivo de imagen.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif, svg, webp.',
            'image.max' => 'El tamaño máximo permitido para la imagen es de 2MB.'
        ];

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], $messages);

        $directory = 'profiles/' . $student_id;

        $extensions = ['jpeg', 'png', 'jpg', 'gif', 'svg','webp'];

        foreach ($extensions as $extension) {
            $existingImage = $directory . '/photo.' . $extension;
            if (Storage::disk('local')->exists($existingImage)) {
                Storage::disk('local')->delete($existingImage);
                break;
            }
        }

        $imageName = 'photo.' . $request->image->extension();
        $request->image->storeAs($directory, $imageName);

        session()->forget('student_form_data_' . $student_id);

        return redirect()->route('system.student.profile', ['student_id' => $student_id])
            ->with('success', 'Imagen subida correctamente.');
    }

    public function get_image($student_id)
    {
        $user = Auth::user();
        $student = Student::find($student_id);

        if (!$student) {
            abort(404);
        }

        if ($user->role_id != 1 && $user->crew_id != $student->crew_id) {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }

        $path_png = 'profiles/' . $student_id . '/photo.png';
        $path_jpg = 'profiles/' . $student_id . '/photo.jpg';
        $path_webp = 'profiles/' . $student_id . '/photo.webp';

        if (Storage::disk('local')->exists($path_png)) {
            $path = $path_png;
        } elseif (Storage::disk('local')->exists($path_jpg)) {
            $path = $path_jpg;
        } elseif (Storage::disk('local')->exists($path_webp)) {
            $path = $path_webp;
        } else {

            $nophotoPath = str_replace('/intranet/public/', '/public_html/intranet/', public_path('assets/img/nophoto.jpg'));
            $nophotoPath = str_replace('/intranet_dev/public/', '/public_html/intranet_dev/', $nophotoPath);
            return response()->file($nophotoPath);
        }

        $fullPath = storage_path('app/' . $path);
        $file = Storage::disk('local')->get($path);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $fullPath);
        finfo_close($finfo);

        return response($file, 200)->header('Content-Type', $type);
    }

    public function search()
    {
        $students = [];
        return view('system.students.search', compact('students'));
    }

    public function searchPost(Request $request)
    {
        $user = Auth::user();

        $students = Student::where(function ($query) use ($request) {
            $data = '%' . $request->data . '%';
            $query->where('name', 'LIKE', $data)
                  ->orWhere('surnames', 'LIKE', $data)
                  ->orWhere('id', 'LIKE', $data);
        });

        if ($user && $user->role_id != 1) {
            $students->where('crew_id', '=', $user->crew_id);
        };

        $students = $students->get();

        return view('system.students.search', compact('students'));
    }

    public function profile($student_id)
    {
        $user = Auth::user();
        $student = Student::find($student_id);

        if (!$student) {
            abort(404);
        }

        if ($user->role_id != 1 && $user->crew_id != $student->crew_id) {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }

        $schedules = Schedule::all();
        $payment_periodicities = PaymentPeriodicity::all();
        $modalities = Modality::all();

        $savedData = session('student_form_data_' . $student_id, []);

        if ($student->first_time) {
            return view('system.students.new-profile', compact('student', 'schedules', 'payment_periodicities', 'modalities', 'savedData'));
        } else {
            $birthdate = DateTime::createFromFormat('d/m/Y', $student->birthdate);
            $nowdate = new DateTime();
            $age = $birthdate->diff($nowdate)->y;
            return view('system.students.profile', compact('student', 'schedules', 'payment_periodicities', 'modalities', 'age'));
        }
    }

    public function update(Request $request)
    {
        if ($request->operation == "new") {
            $studentRules = (new StudentRequest())->rules();
        } else {
            $studentRules = (new StudentUpdateRequest())->rules();
        }
        $tutorRules = (new TutorRequest())->rules();

        $allData = $request->all();
        $allRules = array_merge($studentRules, $tutorRules);
        $validator = Validator::make($allData, $allRules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $student = Student::find($request->student_id);

        if (!$student) {
            abort(404);
        }

        $user = Auth::user();
        if ($user->role_id != 1 && $user->crew_id != $student->crew_id) {
            abort(403);
        }
        $wasFirstTime = $student->first_time;
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

        if ($request->operation == "new") {
            $student->birthdate = $request->birthdate;
            $student->curp = $request->curp;
            $student->payment_periodicity_id = $request->payment_periodicity_id;
            $student->start = $request->start;
            $student->generation = $request->gen_month.'-'.$request->gen_year;
            $student->tuition = $request->tuition;
            $student->first_time = false;

            Tutor::create([
                'student_id' => $student->id,
                'tutor_name' => $request->tutor_name,
                'tutor_surnames' => $request->tutor_surnames,
                'tutor_phone' => $request->tutor_phone,
                'tutor_cel_phone' => $request->tutor_cel_phone,
                'relationship' => $request->relationship
            ]);

        } else {
            $student->tutor->tutor_name = $request->tutor_name;
            $student->tutor->tutor_surnames = $request->tutor_surnames;
            $student->tutor->tutor_phone = $request->tutor_phone;
            $student->tutor->tutor_cel_phone = $request->tutor_cel_phone;
            $student->tutor->relationship = $request->relationship;
        }

        $student->save();

        if ($request->operation == "new" && $wasFirstTime) {
            $generation = $student->generation;
            $plainPassword = session('student_plain_password_' . $student->id);

            if ($this->shouldSendStudentWelcome($generation)) {
                if (!$plainPassword) {
                    $plainPassword = Str::random(8);
                    $student->password = Hash::make($plainPassword);
                    $student->save();
                }

                if (!empty($student->email)) {
                    Mail::to($student->email)->send(new StudentWelcome($student, $plainPassword));
                }
            }

            session()->forget('student_plain_password_' . $student->id);
        }

        if ($request->has('observation') && !empty($request->observation)) {
            Observation::create([
                'student_id' => $student->id,
                'description' => $request->observation,
                'user_id' => Auth::id()
            ]);
        }

        if ($request->operation == "new") {
            return redirect()->route('system.students.search')->with('success', 'Estudiante registrado correctamente');
        } else {
            return back()->with('success', 'Estudiante actualizado correctamente');
        }
    }

    public function requestTuitionChange(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'new_tuition' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:500'
        ]);

        $existingRequest = SysRequest::where('student_id', $request->student_id)
            ->where('request_type_id', 3)
            ->whereNull('approved')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Ya existe una solicitud de cambio de colegiatura pendiente para este estudiante.');
        }

        SysRequest::create([
            'request_type_id' => 3,
            'description' => 'Nueva colegiatura: $' . number_format($request->new_tuition, 2) . ' - ' . $request->reason,
            'user_id' => Auth::id(),
            'student_id' => $request->student_id
        ]);

        return back()->with('success', 'Solicitud de cambio de colegiatura enviada correctamente.');
    }

    public function addObservation(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'description' => 'required|string|max:1000'
        ]);

        Observation::create([
            'student_id' => $request->student_id,
            'description' => $request->description,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Observación agregada correctamente.');
    }

    public function uploadDocument(Request $request)
    {
        $messages = [
            'document_file.required' => 'Es necesario que seleccione un archivo.',
            'document_file.file' => 'El archivo debe ser válido.',
            'document_file.mimes' => 'El archivo debe ser una imagen o PDF.',
            'document_file.max' => 'El tamaño máximo permitido para el archivo es de 5MB.'
        ];

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'document_id' => 'required|exists:student_documents,id',
            'document_file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,pdf|max:5120'
        ], $messages);

        $student_id = $request->student_id;
        $student = Student::find($student_id);

        if (!$student) {
            abort(404);
        }

        $user = Auth::user();
        if ($user->role_id != 1 && $user->crew_id != $student->crew_id) {
            abort(403);
        }

        $document_id = $request->document_id;

        $document = \App\Models\StudentDocument::find($document_id);

        $directory = 'profiles/' . $student_id;

        $safeDocName = preg_replace('/[^a-z0-9_]/', '', str_replace(' ', '_', strtolower($document->name)));
        $fileName = $safeDocName . '.' . $request->document_file->extension();

        $extensions = ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp', 'pdf'];
        foreach ($extensions as $ext) {
            $existingFile = $directory . '/' . $safeDocName . '.' . $ext;
            if (Storage::disk('local')->exists($existingFile)) {
                Storage::disk('local')->delete($existingFile);
            }
        }

        $request->document_file->storeAs($directory, $fileName);

        $student->documents()->updateExistingPivot($document_id, ['uploaded' => true]);

        return redirect()->route('system.student.profile', ['student_id' => $student_id])
            ->with('success', 'Documento subido correctamente.');
    }

    public function getDocument($student_id, $document_id)
    {
        $user = Auth::user();
        $student = Student::find($student_id);
        $document = \App\Models\StudentDocument::find($document_id);

        if (!$document || !$student) {
            abort(404);
        }

        if ($user->role_id != 1 && $user->crew_id != $student->crew_id) {
            abort(403, 'No tienes permiso para acceder a este recurso.');
        }

        $directory = 'profiles/' . $student_id;
        $baseFileName = preg_replace('/[^a-z0-9_]/', '', str_replace(' ', '_', strtolower($document->name)));

        $extensions = ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp', 'pdf'];
        $filePath = null;

        foreach ($extensions as $ext) {
            $possiblePath = $directory . '/' . $baseFileName . '.' . $ext;
            if (Storage::disk('local')->exists($possiblePath)) {
                $filePath = $possiblePath;
                break;
            }
        }

        if (!$filePath) {
            abort(404);
        }

        $fullPath = storage_path('app/' . $filePath);
        $file = Storage::disk('local')->get($filePath);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $fullPath);
        finfo_close($finfo);

        return response($file, 200)->header('Content-Type', $type);
    }

    private function shouldSendStudentWelcome(?string $generation): bool
    {
        if (!$generation) {
            return false;
        }

        if (!preg_match('/^([FMAN])-([0-9]{2})$/', strtoupper($generation), $matches)) {
            return false;
        }

        $year = (int) $matches[2];

        return $year >= 26;
    }
}
