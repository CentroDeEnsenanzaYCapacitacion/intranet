<?php

namespace Tests\Feature\System;

use App\Models\Amount;
use App\Models\Course;
use App\Models\Crew;
use App\Models\Modality;
use App\Models\Paybill;
use App\Models\PaymentType;
use App\Models\Receipt;
use App\Models\ReceiptAttribute;
use App\Models\ReceiptType;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $director;
    protected User $staff;
    protected Crew $crew;
    protected Course $course;
    protected Student $student;
    protected ReceiptType $receiptType;
    protected PaymentType $paymentType;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['id' => 1], ['name' => 'Administrador']);
        Role::firstOrCreate(['id' => 2], ['name' => 'Director']);
        Role::firstOrCreate(['id' => 3], ['name' => 'Staff']);

        $this->crew = Crew::create([
            'name' => 'Plantel Test',
            'adress' => 'Direccion Test',
            'phone' => '5550000000',
            'mail' => 'test@test.com',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'username' => 'admin',
            'name' => 'Admin',
            'surnames' => 'Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'crew_id' => 1,
            'is_active' => true,
            'genre' => 'M',
        ]);

        $this->director = User::create([
            'username' => 'director',
            'name' => 'Director',
            'surnames' => 'Test',
            'email' => 'director@test.com',
            'password' => bcrypt('password'),
            'role_id' => 2,
            'crew_id' => $this->crew->id,
            'is_active' => true,
            'genre' => 'M',
        ]);

        $this->staff = User::create([
            'username' => 'staff',
            'name' => 'Staff',
            'surnames' => 'Test',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role_id' => 3,
            'crew_id' => $this->crew->id,
            'is_active' => true,
            'genre' => 'M',
        ]);

        $this->course = Course::create([
            'name' => 'Licenciatura Test',
            'crew_id' => $this->crew->id,
            'is_active' => true,
        ]);

        $modality = Modality::firstOrCreate(['name' => 'Presencial']);
        $schedule = Schedule::firstOrCreate(['name' => 'Matutino']);

        Student::unguard();
        $this->student = Student::create([
            'name' => 'Estudiante',
            'surnames' => 'Test',
            'curp' => 'TEST123456HTSLRN09',
            'email' => 'student@test.com',
            'phone' => '5550000000',
            'cel_phone' => '5551111111',
            'genre' => 'M',
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'modality_id' => $modality->id,
            'schedule_id' => $schedule->id,
            'generation' => '2024',
            'tuition' => 3500.00,
        ]);
        Student::reguard();

        $this->receiptType = ReceiptType::create(['name' => 'Colegiatura']);
        $this->paymentType = PaymentType::create(['name' => 'Efectivo']);
    }

    public function test_admin_can_view_collection_menu(): void
    {
        $response = $this->actingAs($this->admin)->get('/system/collection/menu');

        $response->assertStatus(200);
        $response->assertViewIs('system.collection.menu');
    }

    public function test_director_can_view_collection_menu(): void
    {
        $response = $this->actingAs($this->director)->get('/system/collection/menu');

        $response->assertStatus(200);
    }

    public function test_staff_can_view_collection_menu(): void
    {
        $response = $this->actingAs($this->staff)->get('/system/collection/menu');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_collection(): void
    {
        $response = $this->get('/system/collection/menu');

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_tuitions_search(): void
    {
        $response = $this->actingAs($this->admin)->get('/system/collection/tuition');

        $response->assertStatus(200);
        $response->assertViewIs('system.collection.tuitions.search');
    }

    public function test_admin_can_search_students(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/system/collection/tuition/search', [
                'data' => 'Estudiante',
            ]);

        $response->assertStatus(200);
        $response->assertViewIs('system.collection.tuitions.search');
        $response->assertViewHas('students');
    }

    public function test_search_with_no_results_redirects(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/system/collection/tuition/search', [
                'data' => 'NoExiste123456',
            ]);

        $response->assertRedirect(route('system.collection.tuition'));
    }

    public function test_director_only_sees_own_crew_students(): void
    {
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5552222222',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);

        $otherStudent = Student::create([
            'name' => 'Otro Estudiante',
            'surnames' => 'Test',
            'curp' => 'OTRO123456HTSLRN09',
            'email' => 'otro@test.com',
            'phone' => '5553333333',
            'cel_phone' => '5554444444',
            'genre' => 'F',
            'crew_id' => $otherCrew->id,
            'course_id' => $this->course->id,
            'generation' => '2024',
            'tuition' => 3500.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->director)
            ->post('/system/collection/tuition/search', [
                'data' => 'Otro',
            ]);

        $response->assertRedirect(route('system.collection.tuition'));
    }

    public function test_director_cannot_view_other_crew_student_tuitions(): void
    {
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5552222222',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);

        $modality = Modality::first();
        $schedule = Schedule::first();

        $otherStudent = Student::create([
            'name' => 'Otro Estudiante',
            'surnames' => 'Test',
            'curp' => 'OTRO123456HTSLRN09',
            'email' => 'otro@test.com',
            'phone' => '5553333333',
            'cel_phone' => '5554444444',
            'genre' => 'F',
            'crew_id' => $otherCrew->id,
            'course_id' => $this->course->id,
            'modality_id' => $modality->id,
            'schedule_id' => $schedule->id,
            'generation' => '2024',
            'tuition' => 3500.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->director)
            ->get('/system/collection/' . $otherStudent->id . '/tuitions');

        $response->assertStatus(403);
    }

    public function test_student_without_complete_data_shows_error(): void
    {
        $incompleteStudent = Student::create([
            'name' => 'Incompleto',
            'surnames' => 'Test',
            'curp' => 'INCO123456HTSLRN09',
            'email' => 'incompleto@test.com',
            'phone' => '5555555555',
            'cel_phone' => '5556666666',
            'genre' => 'M',
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'tuition' => 3500.00,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/system/collection/' . $incompleteStudent->id . '/tuitions');

        $response->assertRedirect(route('system.collection.tuition'));
        $response->assertSessionHasErrors('error');
    }

    public function test_admin_can_view_new_tuition_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/system/collection/' . $this->student->id . '/newtuition');

        $response->assertStatus(200);
        $response->assertViewIs('system.collection.tuitions.new');
        $response->assertViewHas('student');
        $response->assertViewHas('receipt_types');
    }

    public function test_admin_can_view_paybills_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/system/collection/paybills');

        $response->assertStatus(200);
        $response->assertViewIs('system.collection.paybills.show');
    }

    public function test_admin_can_view_new_paybill_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/system/collection/paybills/new');

        $response->assertStatus(200);
        $response->assertViewIs('system.collection.paybills.new');
        $response->assertViewHas('users');
    }

    public function test_admin_can_create_paybill(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/system/collection/paybill/insert', [
                'user_id' => $this->admin->id,
                'receives' => 'Juan Perez',
                'concept' => 'Pago de servicio',
                'crew_id' => $this->crew->id,
                'amount' => '1500.00',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('paybills', [
            'user_id' => $this->admin->id,
            'receives' => 'Juan Perez',
            'concept' => 'Pago de servicio',
            'amount' => '1500.00',
        ]);
    }

    public function test_student_not_found_returns_404(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/system/collection/99999/tuitions');

        $response->assertStatus(404);
    }
}
