<?php

namespace Tests\Feature\System;

use App\Models\Amount;
use App\Models\Course;
use App\Models\Crew;
use App\Models\Marketing;
use App\Models\ReceiptType;
use App\Models\Report;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $directorRole;
    private Role $staffRole;
    private Crew $crew;
    private Course $course;
    private Marketing $marketing;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        Role::firstOrCreate(['id' => 2], ['name' => 'manager']);
        Role::firstOrCreate(['id' => 3], ['name' => 'staff']);
        $this->directorRole = Role::firstOrCreate(['id' => 4], ['name' => 'director']);
        Role::firstOrCreate(['id' => 5], ['name' => 'role5']);
        Role::firstOrCreate(['id' => 6], ['name' => 'vendedor']);
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

        ReceiptType::firstOrCreate(['id' => 1], ['name' => 'Inscripcion']);
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

    private function createReport(User $user, array $overrides = []): Report
    {
        $data = array_merge([
            'name' => 'Prospecto',
            'surnames' => 'Test',
            'email' => 'prospecto' . uniqid() . '@test.com',
            'phone' => '5550000',
            'cel_phone' => '5551111',
            'course_id' => $this->course->id,
            'marketing_id' => $this->marketing->id,
            'crew_id' => $user->crew_id,
            'responsible_id' => $user->id,
            'genre' => 'M',
            'signed' => false,
        ], $overrides);

        return Report::create($data);
    }

    public function test_admin_can_view_reports_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/system/reports');

        $response->assertStatus(200);
        $response->assertViewIs('system.reports.show');
    }

    public function test_director_can_view_reports_list(): void
    {
        $director = $this->createUser($this->directorRole);

        $response = $this->actingAs($director)->get('/system/reports');

        $response->assertStatus(200);
    }

    public function test_staff_cannot_view_reports_list(): void
    {
        $staff = $this->createUser($this->staffRole);

        $response = $this->actingAs($staff)->get('/system/reports');

        $response->assertStatus(403);
    }

    public function test_admin_sees_all_reports(): void
    {
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5551111',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);
        $otherUser = $this->createUser($this->directorRole, ['crew_id' => $otherCrew->id]);

        $this->createReport($this->admin);
        $this->createReport($otherUser);

        $response = $this->actingAs($this->admin)->get('/system/reports');

        $reports = $response->viewData('crew_reports');
        $this->assertEquals(2, $reports->count());
    }

    public function test_director_sees_only_own_crew_reports(): void
    {
        $directorCrew = Crew::create([
            'name' => 'Plantel Director',
            'adress' => 'Direccion Director',
            'phone' => '5552222',
            'mail' => 'director@test.com',
            'is_active' => true,
        ]);
        $director = $this->createUser($this->directorRole, ['crew_id' => $directorCrew->id]);
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5551111',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);
        $otherUser = $this->createUser($this->directorRole, ['crew_id' => $otherCrew->id]);

        $this->createReport($director);
        $this->createReport($otherUser);

        $response = $this->actingAs($director)->get('/system/reports');

        $reports = $response->viewData('crew_reports');
        $this->assertEquals(1, $reports->count());
    }

    public function test_admin_can_view_new_report_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/system/report/new');

        $response->assertStatus(200);
        $response->assertViewIs('system.reports.new');
    }

    public function test_admin_can_create_report(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/system/report/insert', [
                'name' => 'Nuevo Prospecto',
                'surnames' => 'Apellidos',
                'email' => 'nuevo@test.com',
                'phone' => '5550000000',
                'cel_phone' => '5551111111',
                'course_id' => $this->course->id,
                'marketing_id' => $this->marketing->id,
                'crew_id' => $this->crew->id,
                'genre' => 'M',
            ]);

        $response->assertRedirect(route('system.reports.show'));
        $this->assertDatabaseHas('reports', [
            'name' => 'Nuevo Prospecto',
            'surnames' => 'Apellidos',
            'email' => 'nuevo@test.com',
            'signed' => false,
        ]);
    }

    public function test_create_report_detects_duplicates(): void
    {
        $this->createReport($this->admin, [
            'name' => 'Existente',
            'surnames' => 'Test',
            'email' => 'existente@test.com',
        ]);

        $response = $this->actingAs($this->admin)
            ->post('/system/report/insert', [
                'name' => 'Existente',
                'surnames' => 'Test',
                'email' => 'otro@test.com',
                'phone' => '5550000000',
                'cel_phone' => '5551111111',
                'course_id' => $this->course->id,
                'marketing_id' => $this->marketing->id,
                'crew_id' => $this->crew->id,
                'genre' => 'M',
            ]);

        $response->assertSessionHasErrors('duplicado');
    }

    public function test_admin_can_view_set_amount_page(): void
    {
        $report = $this->createReport($this->admin);

        $response = $this->actingAs($this->admin)
            ->get('/system/report/setamount/' . $report->id);

        $response->assertStatus(200);
        $response->assertViewIs('system.reports.set_amount');
    }

    public function test_director_cannot_view_other_crew_set_amount(): void
    {
        $directorCrew = Crew::create([
            'name' => 'Plantel Director',
            'adress' => 'Direccion Director',
            'phone' => '5552222',
            'mail' => 'director@test.com',
            'is_active' => true,
        ]);
        $director = $this->createUser($this->directorRole, ['crew_id' => $directorCrew->id]);
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5551111',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);
        $otherUser = $this->createUser($this->directorRole, ['crew_id' => $otherCrew->id]);
        $report = $this->createReport($otherUser);

        $response = $this->actingAs($director)
            ->get('/system/report/setamount/' . $report->id);

        $response->assertStatus(403);
    }

    public function test_get_inscription_amount_returns_amount(): void
    {
        Amount::create([
            'crew_id' => 1,
            'course_id' => $this->course->id,
            'receipt_type_id' => 1,
            'amount' => 2500,
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/system/get-inscription-amount/' . $this->course->id);

        $response->assertOk();
        $response->assertJson(['amount' => 2500]);
    }

    public function test_get_inscription_amount_returns_null_when_not_found(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/system/get-inscription-amount/' . $this->course->id);

        $response->assertOk();
        $response->assertJson(['amount' => null]);
    }

    public function test_guest_cannot_access_reports(): void
    {
        $response = $this->get('/system/reports');

        $response->assertRedirect(route('login'));
    }
}
