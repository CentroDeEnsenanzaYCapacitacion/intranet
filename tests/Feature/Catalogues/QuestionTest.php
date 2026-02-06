<?php

namespace Tests\Feature\Catalogues;

use App\Models\Course;
use App\Models\Crew;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $managerRole;
    private Role $profesorRole;
    private Role $staffRole;
    private Crew $crew;
    private Course $course;
    private Subject $subject;
    private User $profesor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        $this->managerRole = Role::firstOrCreate(['id' => 2], ['name' => 'manager']);
        $this->staffRole = Role::firstOrCreate(['id' => 3], ['name' => 'staff']);
        $this->profesorRole = Role::firstOrCreate(['id' => 7], ['name' => 'profesor']);

        $this->crew = Crew::create([
            'name' => 'Plantel Test',
            'adress' => 'Direccion Test',
            'phone' => '5550000',
            'mail' => 'plantel@test.com',
            'is_active' => true,
        ]);

        $this->course = Course::create([
            'name' => 'Bachillerato Test',
            'crew_id' => $this->crew->id,
            'is_active' => true,
        ]);

        $this->subject = Subject::create([
            'name' => 'Matematicas',
            'course_id' => $this->course->id,
            'is_active' => true,
        ]);

        $this->profesor = $this->createUser($this->profesorRole);
    }

    private function createUser(Role $role, array $overrides = []): User
    {
        $data = array_merge([
            'name' => 'User',
            'surnames' => 'Test',
            'username' => 'user_' . uniqid(),
            'role_id' => $role->id,
            'crew_id' => $this->crew->id,
            'genre' => 'M',
            'email' => 'user_' . uniqid() . '@capacitacioncec.edu.mx',
            'password' => Hash::make('SecretPassw0rd!'),
            'is_active' => true,
        ], $overrides);

        return User::create($data);
    }

    private function createQuestion(User $creator): Question
    {
        $question = Question::create([
            'question_text' => 'Pregunta de prueba',
            'subject_id' => $this->subject->id,
            'difficulty' => 'medio',
            'created_by' => $creator->id,
            'is_active' => true,
        ]);

        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Opcion 1',
            'is_correct' => true,
            'option_order' => 1,
        ]);

        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Opcion 2',
            'is_correct' => false,
            'option_order' => 2,
        ]);

        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Opcion 3',
            'is_correct' => false,
            'option_order' => 3,
        ]);

        return $question;
    }

    public function test_profesor_can_view_questions_list(): void
    {
        $response = $this->actingAs($this->profesor)->get('/admin/catalogues/questions');

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.questions.show');
    }

    public function test_admin_can_view_questions_list(): void
    {
        $admin = $this->createUser($this->adminRole);

        $response = $this->actingAs($admin)->get('/admin/catalogues/questions');

        $response->assertStatus(200);
    }

    public function test_staff_cannot_view_questions_list(): void
    {
        $staff = $this->createUser($this->staffRole);

        $response = $this->actingAs($staff)->get('/admin/catalogues/questions');

        $response->assertStatus(403);
    }

    public function test_profesor_can_view_create_form(): void
    {
        $response = $this->actingAs($this->profesor)->get('/admin/catalogues/question/new');

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.questions.new');
    }

    public function test_admin_cannot_view_create_form(): void
    {
        $admin = $this->createUser($this->adminRole);

        $response = $this->actingAs($admin)->get('/admin/catalogues/question/new');

        $response->assertRedirect(route('admin.catalogues.questions.show'));
    }

    public function test_profesor_can_create_question(): void
    {
        $response = $this->actingAs($this->profesor)
            ->post('/admin/catalogues/question/insert', [
                'question_text' => 'Nueva pregunta de matematicas',
                'subject_id' => $this->subject->id,
                'difficulty' => 'facil',
                'options' => [
                    ['text' => 'Opcion A'],
                    ['text' => 'Opcion B'],
                    ['text' => 'Opcion C'],
                ],
                'correct_option' => 0,
            ]);

        $response->assertRedirect(route('admin.catalogues.questions.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('questions', [
            'question_text' => 'Nueva pregunta de matematicas',
            'difficulty' => 'facil',
            'created_by' => $this->profesor->id,
        ]);
    }

    public function test_create_validates_required_fields(): void
    {
        $response = $this->actingAs($this->profesor)
            ->post('/admin/catalogues/question/insert', []);

        $response->assertSessionHasErrors(['question_text', 'subject_id', 'difficulty', 'options', 'correct_option']);
    }

    public function test_create_validates_minimum_options(): void
    {
        $response = $this->actingAs($this->profesor)
            ->post('/admin/catalogues/question/insert', [
                'question_text' => 'Pregunta',
                'subject_id' => $this->subject->id,
                'difficulty' => 'facil',
                'options' => [
                    ['text' => 'Opcion A'],
                    ['text' => 'Opcion B'],
                ],
                'correct_option' => 0,
            ]);

        $response->assertSessionHasErrors('options');
    }

    public function test_create_validates_difficulty_values(): void
    {
        $response = $this->actingAs($this->profesor)
            ->post('/admin/catalogues/question/insert', [
                'question_text' => 'Pregunta',
                'subject_id' => $this->subject->id,
                'difficulty' => 'invalido',
                'options' => [
                    ['text' => 'Opcion A'],
                    ['text' => 'Opcion B'],
                    ['text' => 'Opcion C'],
                ],
                'correct_option' => 0,
            ]);

        $response->assertSessionHasErrors('difficulty');
    }

    public function test_profesor_can_view_edit_form_for_own_question(): void
    {
        $question = $this->createQuestion($this->profesor);

        $response = $this->actingAs($this->profesor)->get('/admin/catalogues/question/edit/' . $question->id);

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.questions.edit');
        $response->assertViewHas('question');
    }

    public function test_profesor_can_update_own_question(): void
    {
        $question = $this->createQuestion($this->profesor);

        $response = $this->actingAs($this->profesor)
            ->put('/admin/catalogues/question/update/' . $question->id, [
                'question_text' => 'Pregunta actualizada',
                'subject_id' => $this->subject->id,
                'difficulty' => 'dificil',
                'options' => [
                    ['text' => 'Nueva Opcion A'],
                    ['text' => 'Nueva Opcion B'],
                    ['text' => 'Nueva Opcion C'],
                ],
                'correct_option' => 1,
            ]);

        $response->assertRedirect(route('admin.catalogues.questions.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_text' => 'Pregunta actualizada',
            'difficulty' => 'dificil',
        ]);
    }

    public function test_profesor_cannot_update_other_profesor_question(): void
    {
        $otherProfesor = $this->createUser($this->profesorRole);
        $question = $this->createQuestion($otherProfesor);

        $response = $this->actingAs($this->profesor)
            ->put('/admin/catalogues/question/update/' . $question->id, [
                'question_text' => 'Intento de modificacion',
                'subject_id' => $this->subject->id,
                'difficulty' => 'facil',
                'options' => [
                    ['text' => 'Opcion A'],
                    ['text' => 'Opcion B'],
                    ['text' => 'Opcion C'],
                ],
                'correct_option' => 0,
            ]);

        $response->assertRedirect(route('admin.catalogues.questions.show'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_text' => 'Pregunta de prueba',
        ]);
    }

    public function test_profesor_can_delete_own_question(): void
    {
        $question = $this->createQuestion($this->profesor);

        $response = $this->actingAs($this->profesor)
            ->delete('/admin/catalogues/question/delete/' . $question->id);

        $response->assertRedirect(route('admin.catalogues.questions.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'is_active' => false,
        ]);
    }

    public function test_profesor_cannot_delete_other_profesor_question(): void
    {
        $otherProfesor = $this->createUser($this->profesorRole);
        $question = $this->createQuestion($otherProfesor);

        $response = $this->actingAs($this->profesor)
            ->delete('/admin/catalogues/question/delete/' . $question->id);

        $response->assertRedirect(route('admin.catalogues.questions.show'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_activate_question(): void
    {
        $question = $this->createQuestion($this->profesor);
        $question->update(['is_active' => false]);
        $admin = $this->createUser($this->adminRole);

        $response = $this->actingAs($admin)
            ->put('/admin/catalogues/question/activate/' . $question->id);

        $response->assertRedirect(route('admin.catalogues.questions.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'is_active' => true,
        ]);
    }

    public function test_questions_can_be_filtered_by_subject(): void
    {
        $this->createQuestion($this->profesor);

        $response = $this->actingAs($this->profesor)
            ->get('/admin/catalogues/questions?subject_id=' . $this->subject->id);

        $response->assertStatus(200);
        $response->assertViewHas('questions');
    }

    public function test_questions_can_be_filtered_by_difficulty(): void
    {
        $this->createQuestion($this->profesor);

        $response = $this->actingAs($this->profesor)
            ->get('/admin/catalogues/questions?difficulty=medio');

        $response->assertStatus(200);
        $response->assertViewHas('questions');
    }

    public function test_guest_cannot_access_questions(): void
    {
        $response = $this->get('/admin/catalogues/questions');

        $response->assertRedirect(route('login'));
    }
}
