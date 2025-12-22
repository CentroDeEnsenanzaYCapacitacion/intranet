<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subject;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:1,2,7');
    }

    public function getQuestions(Request $request)
    {
        $query = Question::with(['subject.course', 'options', 'creator'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc');

        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->has('difficulty') && $request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }

        $questions = $query->get();
        $subjects = Subject::where('is_active', true)
            ->whereHas('course', function($q) {
                $q->where('name', 'LIKE', '%bachillerato%');
            })
            ->with('course')
            ->orderBy('name')
            ->get();

        return view('admin.catalogues.questions.show', compact('questions', 'subjects'));
    }

    public function newQuestion()
    {
        $currentUser = Auth::user();

        // Solo los profesores pueden crear preguntas
        if ($currentUser->role_id != 7) {
            return redirect()->route('admin.catalogues.questions.show')
                ->with('error', 'Solo los profesores pueden crear preguntas');
        }

        $subjects = Subject::where('is_active', true)
            ->whereHas('course', function($q) {
                $q->where('name', 'LIKE', '%bachillerato%');
            })
            ->with('course')
            ->orderBy('name')
            ->get();

        return view('admin.catalogues.questions.new', compact('subjects'));
    }

    public function insertQuestion(Request $request)
    {
        $currentUser = Auth::user();

        // Solo los profesores pueden crear preguntas
        if ($currentUser->role_id != 7) {
            return redirect()->route('admin.catalogues.questions.show')
                ->with('error', 'Solo los profesores pueden crear preguntas');
        }

        $request->validate([
            'question_text' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'difficulty' => 'required|in:facil,medio,dificil',
            'options' => 'required|array|min:3|max:6',
            'options.*.text' => 'required|string',
            'correct_option' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $question = Question::create([
                'question_text' => $request->question_text,
                'explanation' => $request->explanation,
                'subject_id' => $request->subject_id,
                'difficulty' => $request->difficulty,
                'created_by' => Auth::id()
            ]);

            foreach ($request->options as $index => $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['text'],
                    'is_correct' => $index == $request->correct_option,
                    'option_order' => $index + 1
                ]);
            }

            DB::commit();
            return redirect()->route('admin.catalogues.questions.show')
                ->with('success', 'Pregunta creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.catalogues.questions.new')
                ->with('error', 'Error al guardar la pregunta: ' . $e->getMessage());
        }
    }

    public function editQuestion($id)
    {
        $question = Question::with(['options', 'subject', 'creator'])->findOrFail($id);
        $currentUser = Auth::user();

        // Cargar materias solo si el usuario puede editar
        $subjects = null;
        if ($currentUser->role_id == 7 && $question->created_by == $currentUser->id) {
            $subjects = Subject::where('is_active', true)
                ->whereHas('course', function($q) {
                    $q->where('name', 'LIKE', '%bachillerato%');
                })
                ->with('course')
                ->orderBy('name')
                ->get();
        }

        return view('admin.catalogues.questions.edit', compact('question', 'subjects'));
    }

    public function updateQuestion(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $currentUser = Auth::user();

        // Solo el profesor creador de la pregunta puede actualizarla
        if ($currentUser->role_id != 7 || $question->created_by != $currentUser->id) {
            return redirect()->route('admin.catalogues.questions.show')
                ->with('error', 'No tienes permiso para actualizar esta pregunta');
        }

        $request->validate([
            'question_text' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'difficulty' => 'required|in:facil,medio,dificil',
            'options' => 'required|array|min:3|max:6',
            'options.*.text' => 'required|string',
            'correct_option' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $question->update([
                'question_text' => $request->question_text,
                'explanation' => $request->explanation,
                'subject_id' => $request->subject_id,
                'difficulty' => $request->difficulty
            ]);

            $question->options()->delete();

            foreach ($request->options as $index => $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['text'],
                    'is_correct' => $index == $request->correct_option,
                    'option_order' => $index + 1
                ]);
            }

            DB::commit();
            return redirect()->route('admin.catalogues.questions.show')
                ->with('success', 'Pregunta actualizada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.catalogues.questions.edit', $id)
                ->with('error', 'Error al actualizar la pregunta: ' . $e->getMessage());
        }
    }

    public function deleteQuestion($id)
    {
        $question = Question::findOrFail($id);
        $currentUser = Auth::user();

        // Solo el profesor creador de la pregunta puede eliminarla
        if ($currentUser->role_id != 7 || $question->created_by != $currentUser->id) {
            return redirect()->route('admin.catalogues.questions.show')
                ->with('error', 'No tienes permiso para eliminar esta pregunta');
        }

        $question->update(['is_active' => false]);

        return redirect()->route('admin.catalogues.questions.show')
            ->with('success', 'Pregunta eliminada exitosamente');
    }

    public function activateQuestion($id)
    {
        $question = Question::findOrFail($id);
        $question->update(['is_active' => true]);

        return redirect()->route('admin.catalogues.questions.show')
            ->with('success', 'Pregunta activada exitosamente');
    }
}
