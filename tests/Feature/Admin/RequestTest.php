<?php

namespace Tests\Feature\Admin;

use App\Models\Course;
use App\Models\Crew;
use App\Models\Marketing;
use App\Models\Paybill;
use App\Models\PaymentType;
use App\Models\Receipt;
use App\Models\ReceiptType;
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
    private RequestType $amountChangeType;

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
        $this->amountChangeType = RequestType::firstOrCreate(['id' => 4], ['name' => 'Cambio de importe']);
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

    private function createReceipt(array $overrides = []): Receipt
    {
        $student = $this->createStudent();
        $receiptType = ReceiptType::firstOrCreate(['id' => 1], ['name' => 'Inscripcion']);
        $paymentType = PaymentType::firstOrCreate(['id' => 1], ['name' => 'Efectivo']);

        $data = array_merge([
            'crew_id' => $this->crew->id,
            'user_id' => $this->admin->id,
            'student_id' => $student->id,
            'receipt_type_id' => $receiptType->id,
            'payment_type_id' => $paymentType->id,
            'concept' => 'Pago test',
            'amount' => 500,
        ], $overrides);

        return Receipt::create($data);
    }

    private function createPaybill(array $overrides = []): Paybill
    {
        $data = array_merge([
            'crew_id' => $this->crew->id,
            'user_id' => $this->admin->id,
            'receives' => 'Proveedor Test',
            'concept' => 'Gasto test',
            'amount' => 300,
        ], $overrides);

        return Paybill::create($data);
    }

    public function test_user_can_request_receipt_amount_change(): void
    {
        $receipt = $this->createReceipt();

        $response = $this->actingAs($this->admin)
            ->post('/admin/stats/billing/request-amount-change', [
                'type' => 'receipt',
                'item_id' => $receipt->id,
                'new_amount' => 750,
                'reason' => 'Error en el monto',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('sys_requests', [
            'request_type_id' => 4,
            'receipt_id' => $receipt->id,
            'approved' => null,
        ]);
    }

    public function test_user_can_request_paybill_amount_change(): void
    {
        $paybill = $this->createPaybill();

        $response = $this->actingAs($this->admin)
            ->post('/admin/stats/billing/request-amount-change', [
                'type' => 'paybill',
                'item_id' => $paybill->id,
                'new_amount' => 400,
                'reason' => 'Monto incorrecto',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('sys_requests', [
            'request_type_id' => 4,
            'paybill_id' => $paybill->id,
            'approved' => null,
        ]);
    }

    public function test_cannot_duplicate_pending_amount_change_request(): void
    {
        $receipt = $this->createReceipt();

        $this->actingAs($this->admin)
            ->post('/admin/stats/billing/request-amount-change', [
                'type' => 'receipt',
                'item_id' => $receipt->id,
                'new_amount' => 750,
                'reason' => 'Primera solicitud',
            ]);

        $response = $this->actingAs($this->admin)
            ->post('/admin/stats/billing/request-amount-change', [
                'type' => 'receipt',
                'item_id' => $receipt->id,
                'new_amount' => 800,
                'reason' => 'Segunda solicitud',
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, SysRequest::where('receipt_id', $receipt->id)->where('request_type_id', 4)->count());
    }

    public function test_amount_change_validates_input(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/stats/billing/request-amount-change', [
                'type' => 'invalid',
                'item_id' => 999,
                'new_amount' => 0,
                'reason' => '',
            ]);

        $response->assertSessionHasErrors(['type', 'new_amount', 'reason']);
    }

    public function test_manager_cannot_request_change_for_other_crew_receipt(): void
    {
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5559999',
            'mail' => 'otro_billing@test.com',
            'is_active' => true,
        ]);
        $receipt = $this->createReceipt(['crew_id' => $otherCrew->id]);
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)
            ->post('/admin/stats/billing/request-amount-change', [
                'type' => 'receipt',
                'item_id' => $receipt->id,
                'new_amount' => 999,
                'reason' => 'Intento IDOR',
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_approve_amount_change_for_receipt(): void
    {
        $receipt = $this->createReceipt(['amount' => 500]);

        $sysRequest = SysRequest::create([
            'request_type_id' => 4,
            'description' => 'Recibo #R-001 | Importe actual: $500.00 | Nuevo importe: $750.00 | Error',
            'user_id' => $this->admin->id,
            'receipt_id' => $receipt->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/request/' . $sysRequest->id . '/approve');

        $response->assertRedirect(route('admin.requests.show'));
        $this->assertDatabaseHas('receipts', [
            'id' => $receipt->id,
            'amount' => '750.00',
        ]);
        $this->assertDatabaseHas('sys_requests', [
            'id' => $sysRequest->id,
            'approved' => true,
        ]);
    }

    public function test_admin_can_approve_amount_change_for_paybill(): void
    {
        $paybill = $this->createPaybill(['amount' => 300]);

        $sysRequest = SysRequest::create([
            'request_type_id' => 4,
            'description' => 'Vale #V-001 | Importe actual: $300.00 | Nuevo importe: $450.00 | Error',
            'user_id' => $this->admin->id,
            'paybill_id' => $paybill->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/request/' . $sysRequest->id . '/approve');

        $response->assertRedirect(route('admin.requests.show'));
        $this->assertDatabaseHas('paybills', [
            'id' => $paybill->id,
            'amount' => '450.00',
        ]);
    }

    public function test_admin_can_change_amount_via_edit(): void
    {
        $receipt = $this->createReceipt(['amount' => 500]);

        $sysRequest = SysRequest::create([
            'request_type_id' => 4,
            'description' => 'Recibo #R-001 | Importe actual: $500.00 | Nuevo importe: $750.00 | Error',
            'user_id' => $this->admin->id,
            'receipt_id' => $receipt->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->post('/admin/request/' . $sysRequest->id . '/amount', [
                'new_amount' => 900,
            ]);

        $response->assertRedirect(route('admin.requests.show'));
        $this->assertDatabaseHas('receipts', [
            'id' => $receipt->id,
            'amount' => 900,
        ]);
        $this->assertDatabaseHas('sys_requests', [
            'id' => $sysRequest->id,
            'approved' => true,
        ]);
    }

    public function test_change_amount_rejects_wrong_request_type(): void
    {
        $discountRequest = $this->createRequest($this->admin, $this->discountType);

        $response = $this->actingAs($this->admin)
            ->post('/admin/request/' . $discountRequest->id . '/amount', [
                'new_amount' => 100,
            ]);

        $response->assertStatus(403);
    }

    public function test_change_amount_rejects_already_approved(): void
    {
        $receipt = $this->createReceipt(['amount' => 500]);

        $sysRequest = SysRequest::create([
            'request_type_id' => 4,
            'description' => 'Recibo #R-001 | Importe actual: $500.00 | Nuevo importe: $750.00 | Error',
            'user_id' => $this->admin->id,
            'receipt_id' => $receipt->id,
            'approved' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->post('/admin/request/' . $sysRequest->id . '/amount', [
                'new_amount' => 999,
            ]);

        $response->assertRedirect(route('admin.requests.show'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('receipts', [
            'id' => $receipt->id,
            'amount' => 500,
        ]);
    }

    public function test_non_admin_cannot_change_amount(): void
    {
        $manager = $this->createUser($this->managerRole);
        $receipt = $this->createReceipt(['amount' => 500]);

        $sysRequest = SysRequest::create([
            'request_type_id' => 4,
            'description' => 'Recibo #R-001 | Importe actual: $500.00 | Nuevo importe: $750.00 | Error',
            'user_id' => $manager->id,
            'receipt_id' => $receipt->id,
        ]);

        $response = $this->actingAs($manager)
            ->post('/admin/request/' . $sysRequest->id . '/amount', [
                'new_amount' => 999,
            ]);

        $response->assertStatus(403);
    }

    public function test_change_amount_validates_input(): void
    {
        $receipt = $this->createReceipt();

        $sysRequest = SysRequest::create([
            'request_type_id' => 4,
            'description' => 'Recibo #R-001 | Importe actual: $500.00 | Nuevo importe: $750.00 | Error',
            'user_id' => $this->admin->id,
            'receipt_id' => $receipt->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->post('/admin/request/' . $sysRequest->id . '/amount', [
                'new_amount' => 0,
            ]);

        $response->assertSessionHasErrors('new_amount');
    }
}
