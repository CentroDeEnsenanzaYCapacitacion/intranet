<?php

namespace Tests\Feature\Students;

use App\Models\Course;
use App\Models\Crew;
use App\Models\Modality;
use App\Models\PaymentPeriodicity;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentAccessTest extends TestCase
{
    use RefreshDatabase;

    private Crew $crewA;
    private Crew $crewB;
    private Course $courseA;
    private Course $courseB;
    private Role $adminRole;
    private Role $managerRole;
    private Role $staffRole;
    private User $admin;
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

        $this->courseA = Course::create([
            'name' => 'Curso A',
            'crew_id' => $this->crewA->id,
        ]);

        $this->courseB = Course::create([
            'name' => 'Curso B',
            'crew_id' => $this->crewB->id,
        ]);

        $this->admin = $this->createUser($this->adminRole, $this->crewA);
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

    public function test_student_search_page_renders(): void
    {
        $response = $this->actingAs($this->staffA)->get('/system/student/search');

        $response->assertStatus(200);
        $response->assertViewIs('system.students.search');
    }

    public function test_student_search_post_filters_by_crew_for_non_admin(): void
    {
        $this->createStudent($this->crewA, $this->courseA, ['name' => 'Carlos']);
        $this->createStudent($this->crewB, $this->courseB, ['name' => 'Carlos']);

        $response = $this->actingAs($this->staffA)->post('/system/student/search', [
            'data' => 'Carlos',
        ]);

        $response->assertStatus(200);
        $response->assertViewHas('students', function ($students) {
            return $students->count() === 1 && $students->first()->crew_id === $this->crewA->id;
        });
    }

    public function test_student_search_post_returns_all_for_admin(): void
    {
        $this->createStudent($this->crewA, $this->courseA, ['name' => 'Lucia']);
        $this->createStudent($this->crewB, $this->courseB, ['name' => 'Lucia']);

        $response = $this->actingAs($this->admin)->post('/system/student/search', [
            'data' => 'Lucia',
        ]);

        $response->assertStatus(200);
        $response->assertViewHas('students', function ($students) {
            return $students->count() === 2;
        });
    }

    public function test_student_profile_denies_other_crew(): void
    {
        $student = $this->createStudent($this->crewB, $this->courseB);

        $response = $this->actingAs($this->staffA)->get('/system/student/profile/' . $student->id);

        $response->assertStatus(403);
    }

    public function test_student_profile_shows_new_profile_view_for_first_time(): void
    {
        $student = $this->createStudent($this->crewA, $this->courseA, ['first_time' => true]);

        $response = $this->actingAs($this->staffA)->get('/system/student/profile/' . $student->id);

        $response->assertStatus(200);
        $response->assertViewIs('system.students.new-profile');
    }

    public function test_student_profile_shows_profile_view_for_existing_student(): void
    {
        $paymentPeriodicity = PaymentPeriodicity::create(['name' => 'Mensual']);
        $schedule = Schedule::create(['name' => 'Matutino']);
        $modality = Modality::create(['name' => 'Presencial']);

        $student = $this->createStudent($this->crewA, $this->courseA, [
            'first_time' => false,
            'birthdate' => '01/01/2000',
            'payment_periodicity_id' => $paymentPeriodicity->id,
            'schedule_id' => $schedule->id,
            'modality_id' => $modality->id,
        ]);

        Tutor::create([
            'student_id' => $student->id,
            'tutor_name' => 'Tutor',
            'tutor_surnames' => 'Test',
            'tutor_phone' => '5550000',
            'tutor_cel_phone' => '5550001',
            'relationship' => 'Padre',
        ]);

        $response = $this->actingAs($this->staffA)->get('/system/student/profile/' . $student->id);

        $response->assertStatus(200);
        $response->assertViewIs('system.students.profile');
    }

    public function test_save_form_data_stores_session(): void
    {
        $student = $this->createStudent($this->crewA, $this->courseA);

        $response = $this->actingAs($this->staffA)->post('/system/student/save-form-data/' . $student->id, [
            'field' => 'value',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertSame('value', session('student_form_data_' . $student->id)['field'] ?? null);
    }

    public function test_upload_profile_image_saves_and_redirects(): void
    {
        Storage::fake('local');

        $student = $this->createStudent($this->crewA, $this->courseA);

        $response = $this->actingAs($this->staffA)->post('/system/student/upload-profile-image/' . $student->id, [
            'image' => UploadedFile::fake()->image('photo.jpg'),
        ]);

        $response->assertRedirect(route('system.student.profile', ['student_id' => $student->id]));
        $response->assertSessionHas('success', 'Imagen subida correctamente.');
        Storage::disk('local')->assertExists('profiles/' . $student->id . '/photo.jpg');
    }

    public function test_upload_profile_image_denies_other_crew(): void
    {
        $student = $this->createStudent($this->crewB, $this->courseB);

        $response = $this->actingAs($this->staffA)->post('/system/student/upload-profile-image/' . $student->id, [
            'image' => UploadedFile::fake()->image('photo.jpg'),
        ]);

        $response->assertStatus(403);
    }

    public function test_get_image_returns_file_for_owner(): void
    {
        $student = $this->createStudent($this->crewA, $this->courseA);
        $path = 'profiles/' . $student->id . '/photo.jpg';

        Storage::disk('local')->put($path, 'content');

        $response = $this->actingAs($this->staffA)->get('/system/student/image/' . $student->id);

        $response->assertStatus(200);

        Storage::disk('local')->delete($path);
    }
}
