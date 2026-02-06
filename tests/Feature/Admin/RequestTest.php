<?php

namespace Tests\Feature\Admin;

use App\Models\Course;
use App\Models\Crew;
use App\Models\Marketing;
use App\Models\Report;
use App\Models\RequestType;
use App\Models\Role;
use App\Models\Student;
use App\Models\SysRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RequestTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $managerRole;
    private Role $staffRole;
    private Crew $crew;
    private Course $course;
    private Marketing $marketing;
    private User $admin;
    private Report $report;
    private RequestType $discountType;
    private RequestType $tuitionType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        $this->managerRole = Role::firstOrCreate(['id' => 2], ['name' => 'manager']);
        $this->staffRole = Role::firstOrCreate(['id' => 3], ['name' => 'staff']);

        $this->crew = Crew::create([
            'name' => 'Plantel Test',
            'adress' => 'Direccion Test',
            'phone' => '5550000',
            'mail' => 'plantel@test.com',
            'is_active' => true,
        ]);

        $this->course = Course::create([
            'name' => 'Curso Test',
            'crew_id' => $this->crew->id,
            'is_active' => true,
        ]);

        $this->marketing = Marketing::create([
            'name' => 'Marketing Test',
        ]);

        $this->admin = $this->createUser($this->adminRole);

        $this->report = Report::create([
            'name' => 'Reporte',
            'surnames' => 'Test',
            'email' => 'reporte@test.com',
            'phone' => '5550000',
            'cel_phone' => '5551111',
            'course_id' => $this->course->id,
            'marketing_id' => $this->marketing->id,
            'crew_id' => $this->crew->id,
            'responsible_id' => $this->admin->id,
            'genre' => 'M',
        ]);

        RequestType::firstOrCreate(['id' => 1], ['name' => 'Inscripcion']);
        $this->discountType = RequestType::firstOrCreate(['id' => 2], ['name' => 'Descuento']);
        $this->tuitionType = RequestType::firstOrCreate(['id' => 3], ['name' => 'Cambio de colegiatura']);
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

    private function createReport(User $user): Report
    {
        return Report::create([
            'name' => 'Reporte',
            'surnames' => 'Test',
            'email' => 'reporte' . uniqid() . '@test.com',
            'phone' => '5550000',
            'cel_phone' => '5551111',
            'course_id' => $this->course->id,
            'marketing_id' => $this->marketing->id,
            'crew_id' => $user->crew_id,
            'responsible_id' => $user->id,
            'genre' => 'M',
        ]);
    }

    private function createRequest(User $user, RequestType $type, array $overrides = []): SysRequest
    {
        $report = $this->createReport($user);

        $data = array_merge([
            'user_id' => $user->id,
            'request_type_id' => $type->id,
            'report_id' => $report->id,
            'description' => '10% - Motivo de prueba',
            'approved' => null,
        ], $overrides);

        return SysRequest::create($data);
    }

    private function createStudent(): Student
    {
        return Student::create([
            'name' => 'Estudiante',
            'surnames' => 'Test Prueba',
            'genre' => 'M',
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'email' => 'student' . uniqid() . '@test.com',
            'phone' => '5550000',
            'cel_phone' => '5551111',
            'tuition' => 1000,
        ]);
    }

    public function test_admin_can_view_requests_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/requests');

        $response->assertStatus(200);
        $response->assertViewIs('admin.requests.show');
        $response->assertViewHas(['requests', 'old_requests']);
    }

    public function test_manager_can_view_requests_list(): void
    {
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)->get('/admin/requests');

        $response->assertStatus(200);
    }

    public function test_staff_can_view_requests_list(): void
    {
        $staff = $this->createUser($this->staffRole);

        $response = $this->actingAs($staff)->get('/admin/requests');

        $response->assertStatus(200);
    }

    public function test_admin_sees_all_requests(): void
    {
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5551111',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);
        $otherUser = $this->createUser($this->staffRole, ['crew_id' => $otherCrew->id]);

        $this->createRequest($this->admin, $this->discountType);
        $this->createRequest($otherUser, $this->discountType);

        $response = $this->actingAs($this->admin)->get('/admin/requests');

        $requests = $response->viewData('requests');
        $this->assertEquals(2, $requests->count());
    }

    public function test_manager_sees_only_own_crew_requests(): void
    {
        $manager = $this->createUser($this->managerRole);
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5551111',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);
        $otherUser = $this->createUser($this->staffRole, ['crew_id' => $otherCrew->id]);

        $this->createRequest($manager, $this->discountType);
        $this->createRequest($otherUser, $this->discountType);

        $response = $this->actingAs($manager)->get('/admin/requests');

        $requests = $response->viewData('requests');
        $this->assertEquals(1, $requests->count());
    }

    public function test_admin_can_approve_request(): void
    {
        $request = $this->createRequest($this->admin, $this->discountType);

        $response = $this->actingAs($this->admin)
            ->get('/admin/request/' . $request->id . '/approve');

        $response->assertRedirect(route('admin.requests.show'));
        $this->assertDatabaseHas('sys_requests', [
            'id' => $request->id,
            'approved' => true,
        ]);
    }

    public function test_admin_can_decline_request(): void
    {
        $request = $this->createRequest($this->admin, $this->discountType);

        $response = $this->actingAs($this->admin)
            ->get('/admin/request/' . $request->id . '/decline');

        $response->assertRedirect(route('admin.requests.show'));
        $this->assertDatabaseHas('sys_requests', [
            'id' => $request->id,
            'approved' => false,
        ]);
    }

    public function test_non_admin_cannot_approve_request(): void
    {
        $manager = $this->createUser($this->managerRole);
        $request = $this->createRequest($manager, $this->discountType);

        $response = $this->actingAs($manager)
            ->get('/admin/request/' . $request->id . '/approve');

        $response->assertStatus(403);
        $this->assertDatabaseHas('sys_requests', [
            'id' => $request->id,
            'approved' => null,
        ]);
    }

    public function test_admin_can_view_edit_request(): void
    {
        $request = $this->createRequest($this->admin, $this->discountType);

        $response = $this->actingAs($this->admin)
            ->get('/admin/request/' . $request->id);

        $response->assertStatus(200);
        $response->assertViewIs('admin.requests.edit');
    }

    public function test_non_admin_cannot_view_edit_request(): void
    {
        $manager = $this->createUser($this->managerRole);
        $request = $this->createRequest($manager, $this->discountType);

        $response = $this->actingAs($manager)
            ->get('/admin/request/' . $request->id);

        $response->assertStatus(403);
    }

    public function test_admin_can_change_percentage(): void
    {
        $request = $this->createRequest($this->admin, $this->discountType, [
            'description' => '10% - Motivo original',
        ]);

        $response = $this->actingAs($this->admin)
            ->post('/admin/request/' . $request->id, [
                'discount' => '15%',
            ]);

        $response->assertRedirect(route('admin.requests.show'));
        $this->assertDatabaseHas('sys_requests', [
            'id' => $request->id,
            'description' => '15% -  Motivo original',
        ]);
    }

    public function test_admin_can_change_tuition(): void
    {
        $student = $this->createStudent();

        $request = $this->createRequest($this->admin, $this->tuitionType, [
            'student_id' => $student->id,
            'description' => 'Nueva colegiatura: $1500.00',
        ]);

        $response = $this->actingAs($this->admin)
            ->post('/admin/request/' . $request->id . '/tuition', [
                'new_tuition' => 1200,
            ]);

        $response->assertRedirect(route('admin.requests.show'));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'tuition' => 1200,
        ]);
        $this->assertDatabaseHas('sys_requests', [
            'id' => $request->id,
            'approved' => true,
        ]);
    }

    public function test_change_tuition_validates_amount(): void
    {
        $student = $this->createStudent();

        $request = $this->createRequest($this->admin, $this->tuitionType, [
            'student_id' => $student->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->post('/admin/request/' . $request->id . '/tuition', [
                'new_tuition' => 0,
            ]);

        $response->assertSessionHasErrors('new_tuition');
    }

    public function test_approve_tuition_request_updates_student_tuition(): void
    {
        $student = $this->createStudent();

        $request = $this->createRequest($this->admin, $this->tuitionType, [
            'student_id' => $student->id,
            'description' => 'Nueva colegiatura: $1500.00',
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/request/' . $request->id . '/approve');

        $response->assertRedirect(route('admin.requests.show'));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'tuition' => 1500.00,
        ]);
    }

    public function test_guest_cannot_access_requests(): void
    {
        $response = $this->get('/admin/requests');

        $response->assertRedirect(route('login'));
    }
}
