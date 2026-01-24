<?php

namespace Tests\Feature\Students;

use App\Models\Course;
use App\Models\Crew;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\StudentExamWindow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentDocumentsAndCalendarTest extends TestCase
{
    use RefreshDatabase;

    private Crew $crewA;
    private Crew $crewB;
    private Course $eubCourseA;
    private Course $eubCourseB;
    private Role $adminRole;
    private Role $managerRole;
    private Role $staffRole;
    private User $admin;
    private User $managerA;
    private User $staffA;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        $this->managerRole = Role::firstOrCreate(['id' => 2], ['name' => 'manager']);
        $this->staffRole = Role::firstOrCreate(['id' => 3], ['name' => 'staff']);

        $this->crewA = Crew::create([
            'name' => 'Crew A',
            'adress' => 'Address',
            'phone' => '5550000',
            'mail' => 'crew-a@example.com',
        ]);

        $this->crewB = Crew::create([
            'name' => 'Crew B',
            'adress' => 'Address',
            'phone' => '5550001',
            'mail' => 'crew-b@example.com',
        ]);

        $this->eubCourseA = Course::create([
            'name' => 'BACHILLERATO EN UN EXAMEN',
            'crew_id' => $this->crewA->id,
        ]);

        $this->eubCourseB = Course::create([
            'name' => 'BACHILLERATO EN UN EXAMEN',
            'crew_id' => $this->crewB->id,
        ]);

        $this->admin = $this->createUser($this->adminRole, $this->crewA);
        $this->managerA = $this->createUser($this->managerRole, $this->crewA);
        $this->staffA = $this->createUser($this->staffRole, $this->crewA);
    }

    private function createUser(Role $role, Crew $crew, array $overrides = []): User
    {
        $data = array_merge([
            'name' => 'User',
            'surnames' => 'Test',
            'username' => 'user_' . uniqid(),
            'role_id' => $role->id,
            'crew_id' => $crew->id,
            'genre' => 'M',
            'email' => 'user_' . uniqid() . '@capacitacioncec.edu.mx',
            'password' => Hash::make('SecretPassw0rd!'),
            'is_active' => true,
        ], $overrides);

        return User::create($data);
    }

    private function createStudent(Crew $crew, Course $course, array $overrides = []): Student
    {
        $data = array_merge([
            'crew_id' => $crew->id,
            'course_id' => $course->id,
            'name' => 'Student',
            'surnames' => 'Demo',
            'genre' => 'M',
            'email' => 'student_' . uniqid() . '@capacitacioncec.edu.mx',
            'first_time' => true,
        ], $overrides);

        return Student::create($data);
    }

    public function test_upload_document_marks_pivot_uploaded_and_stores_file(): void
    {
        Storage::fake('local');

        $student = $this->createStudent($this->crewA, $this->eubCourseA);
        $document = StudentDocument::create(['name' => 'Acta Nacimiento']);
        $student->documents()->attach($document->id, ['uploaded' => false]);

        $response = $this->actingAs($this->staffA)->post('/system/student/upload-document', [
            'student_id' => $student->id,
            'document_id' => $document->id,
            'document_file' => UploadedFile::fake()->image('doc.jpg'),
        ]);

        $response->assertRedirect(route('system.student.profile', ['student_id' => $student->id]));
        $response->assertSessionHas('success', 'Documento subido correctamente.');

        $student = $student->fresh();
        $pivot = $student->documents()->first()->pivot;
        $this->assertTrue((bool) $pivot->uploaded);
        Storage::disk('local')->assertExists('profiles/' . $student->id . '/acta_nacimiento.jpg');
    }

    public function test_upload_document_denies_other_crew(): void
    {
        $student = $this->createStudent($this->crewB, $this->eubCourseB);
        $document = StudentDocument::create(['name' => 'Acta Nacimiento']);
        $student->documents()->attach($document->id, ['uploaded' => false]);

        $response = $this->actingAs($this->staffA)->post('/system/student/upload-document', [
            'student_id' => $student->id,
            'document_id' => $document->id,
            'document_file' => UploadedFile::fake()->image('doc.jpg'),
        ]);

        $response->assertStatus(403);
    }

    public function test_get_document_denies_other_crew(): void
    {
        $student = $this->createStudent($this->crewB, $this->eubCourseB);
        $document = StudentDocument::create(['name' => 'Acta Nacimiento']);

        $response = $this->actingAs($this->staffA)->get('/system/student/document/' . $student->id . '/' . $document->id);

        $response->assertStatus(403);
    }

    public function test_get_document_returns_file_for_owner(): void
    {
        $student = $this->createStudent($this->crewA, $this->eubCourseA);
        $document = StudentDocument::create(['name' => 'Acta Nacimiento']);

        $path = 'profiles/' . $student->id . '/acta_nacimiento.pdf';
        Storage::disk('local')->put($path, 'content');

        $response = $this->actingAs($this->staffA)->get('/system/student/document/' . $student->id . '/' . $document->id);

        $response->assertStatus(200);

        Storage::disk('local')->delete($path);
    }

    public function test_add_observation_creates_record(): void
    {
        $student = $this->createStudent($this->crewA, $this->eubCourseA);

        $response = $this->actingAs($this->staffA)->post('/system/student/add-observation', [
            'student_id' => $student->id,
            'description' => 'Observacion',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('observations', [
            'student_id' => $student->id,
            'user_id' => $this->staffA->id,
            'description' => 'Observacion',
        ]);
    }

    public function test_eub_update_creates_exam_window(): void
    {
        $student = $this->createStudent($this->crewA, $this->eubCourseA);

        $response = $this->actingAs($this->managerA)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/system/calendars/eub/' . $student->id, [
            'start_at' => '2026-01-01T10:00',
            'end_at' => '2026-01-01T12:00',
        ]);

        $response->assertSessionHas('success', 'Horario guardado correctamente.');
        $this->assertDatabaseHas('student_exam_windows', [
            'student_id' => $student->id,
            'exam_key' => 'eub',
        ]);
    }

    public function test_eub_update_deletes_when_empty(): void
    {
        $student = $this->createStudent($this->crewA, $this->eubCourseA);

        StudentExamWindow::create([
            'student_id' => $student->id,
            'exam_key' => 'eub',
            'start_at' => now(),
            'end_at' => now()->addHour(),
        ]);

        $response = $this->actingAs($this->managerA)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/system/calendars/eub/' . $student->id, []);

        $response->assertSessionHas('success', 'Horario eliminado correctamente.');
        $this->assertDatabaseMissing('student_exam_windows', [
            'student_id' => $student->id,
            'exam_key' => 'eub',
        ]);
    }

    public function test_eub_update_denies_other_crew(): void
    {
        $student = $this->createStudent($this->crewB, $this->eubCourseB);

        $response = $this->actingAs($this->managerA)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/system/calendars/eub/' . $student->id, [
            'start_at' => '2026-01-01T10:00',
            'end_at' => '2026-01-01T12:00',
        ]);

        $response->assertStatus(404);
    }

    public function test_eub_update_validates_end_after_start(): void
    {
        $student = $this->createStudent($this->crewA, $this->eubCourseA);

        $response = $this->actingAs($this->managerA)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/system/calendars/eub/' . $student->id, [
            'start_at' => '2026-01-01T12:00',
            'end_at' => '2026-01-01T10:00',
        ]);

        $response->assertSessionHasErrors('end_at');
    }

    public function test_eub_list_limits_by_crew_for_non_admin(): void
    {
        $this->createStudent($this->crewA, $this->eubCourseA, ['name' => 'Ana']);
        $this->createStudent($this->crewB, $this->eubCourseB, ['name' => 'Ana']);

        $response = $this->actingAs($this->managerA)->get('/system/calendars/eub');

        $response->assertStatus(200);
        $response->assertViewHas('students', function ($students) {
            return $students->count() === 1 && $students->first()->crew_id === $this->crewA->id;
        });
    }
}
