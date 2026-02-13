<?php

namespace Tests\Feature\Catalogues;

use App\Models\Amount;
use App\Models\Course;
use App\Models\Crew;
use App\Models\ReceiptType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AmountTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $managerRole;
    private Role $staffRole;
    private Crew $crew;
    private Course $course;
    private ReceiptType $receiptType;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(
            ['id' => 1],
            ['name' => 'admin']
        );

        $this->managerRole = Role::firstOrCreate(
            ['id' => 2],
            ['name' => 'manager']
        );

        $this->staffRole = Role::firstOrCreate(
            ['id' => 3],
            ['name' => 'staff']
        );

        $this->crew = Crew::create([
            'name' => 'Plantel Test',
            'adress' => 'Direccion Test',
            'phone' => '5550000',
            'mail' => 'plantel@test.com',
            'is_active' => true,
        ]);

        $this->course = Course::create([
            'name' => 'Curso Test',
            'is_active' => true,
            'crew_id' => $this->crew->id,
        ]);

        $this->receiptType = ReceiptType::create([
            'name' => 'Inscripcion',
        ]);

        $this->admin = $this->createUser($this->adminRole);
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

    public function test_admin_can_view_amounts_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/catalogues/amounts');

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.amounts.show');
    }

    public function test_manager_can_view_amounts_list(): void
    {
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)->get('/admin/catalogues/amounts');

        $response->assertStatus(200);
    }

    public function test_staff_cannot_view_amounts_list(): void
    {
        $staff = $this->createUser($this->staffRole);

        $response = $this->actingAs($staff)->get('/admin/catalogues/amounts');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/catalogues/amount/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.amounts.create');
    }

    public function test_admin_can_create_receipt_type_with_amount(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/admin/catalogues/amount/store', [
                'name' => 'Credencial Nueva',
                'amount' => '150.00',
            ]);

        $response->assertRedirect(route('admin.catalogues.amounts.show'));
        $response->assertSessionHas('success', 'Costo creado correctamente');

        $this->assertDatabaseHas('receipt_types', [
            'name' => 'Credencial Nueva',
            'automatic_amount' => false,
        ]);

        $receiptType = ReceiptType::where('name', 'Credencial Nueva')->first();

        $this->assertDatabaseHas('amounts', [
            'receipt_type_id' => $receiptType->id,
            'crew_id' => 1,
            'course_id' => null,
            'amount' => 150.00,
        ]);
    }

    public function test_cannot_create_duplicate_receipt_type(): void
    {
        ReceiptType::create(['name' => 'Constancia']);

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/admin/catalogues/amount/store', [
                'name' => 'Constancia',
                'amount' => '200.00',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_create_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/admin/catalogues/amount/store', []);

        $response->assertSessionHasErrors(['name', 'amount']);
    }

    public function test_create_validates_amount_format(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/admin/catalogues/amount/store', [
                'name' => 'Test',
                'amount' => 'invalid',
            ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_create_validates_amount_max_digits(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/admin/catalogues/amount/store', [
                'name' => 'Test',
                'amount' => '1234567.00',
            ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_admin_can_edit_amount(): void
    {
        $amount = Amount::create([
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'receipt_type_id' => $this->receiptType->id,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/catalogues/amount/edit/' . $amount->id);

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.amounts.edit');
        $response->assertViewHas('amount');
    }

    public function test_admin_can_update_amount(): void
    {
        $amount = Amount::create([
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'receipt_type_id' => $this->receiptType->id,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->put('/admin/catalogues/amount/update/' . $amount->id, [
                'amount' => '2500.75',
            ]);

        $response->assertRedirect(route('admin.catalogues.amounts.show'));
        $response->assertSessionHas('success', 'Costo actualizado correctamente');

        $this->assertDatabaseHas('amounts', [
            'id' => $amount->id,
            'amount' => 2500.75,
        ]);
    }

    public function test_generate_amounts_creates_missing_inscriptions(): void
    {
        $course2 = Course::create([
            'name' => 'Curso 2',
            'is_active' => true,
            'crew_id' => $this->crew->id,
        ]);

        Amount::create([
            'crew_id' => 1,
            'course_id' => $this->course->id,
            'receipt_type_id' => 1,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get('/admin/catalogues/amounts/generate');

        $response->assertRedirect(route('admin.catalogues.amounts.show'));

        $this->assertDatabaseHas('amounts', [
            'crew_id' => 1,
            'course_id' => $course2->id,
            'receipt_type_id' => 1,
        ]);

        $this->assertDatabaseCount('amounts', 2);
    }

    public function test_generate_amounts_does_not_affect_manual_amounts(): void
    {
        $manualAmount = Amount::create([
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'receipt_type_id' => $this->receiptType->id,
            'amount' => 5000,
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get('/admin/catalogues/amounts/generate');

        $response->assertRedirect(route('admin.catalogues.amounts.show'));

        $this->assertDatabaseHas('amounts', [
            'id' => $manualAmount->id,
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'receipt_type_id' => $this->receiptType->id,
            'amount' => 5000,
        ]);
    }

    public function test_admin_can_create_amount_without_password_confirmation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/catalogues/amount/store', [
                'name' => 'Test',
                'amount' => '150.00',
            ]);

        $response->assertRedirect(route('admin.catalogues.amounts.show'));
        $this->assertDatabaseHas('receipt_types', ['name' => 'Test']);
    }

    public function test_admin_can_update_amount_without_password_confirmation(): void
    {
        $amount = Amount::create([
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'receipt_type_id' => $this->receiptType->id,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($this->admin)
            ->put('/admin/catalogues/amount/update/' . $amount->id, [
                'amount' => '2500.75',
            ]);

        $response->assertRedirect(route('admin.catalogues.amounts.show'));
        $this->assertDatabaseHas('amounts', [
            'id' => $amount->id,
            'amount' => 2500.75,
        ]);
    }

    public function test_guest_cannot_access_amounts(): void
    {
        $response = $this->get('/admin/catalogues/amounts');
        $response->assertRedirect(route('login'));
    }

    public function test_manager_cannot_create_amount(): void
    {
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)
            ->post('/admin/catalogues/amount/store', [
                'name' => 'Intento',
                'amount' => '100.00',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('receipt_types', ['name' => 'Intento']);
    }

    public function test_manager_cannot_access_create_form(): void
    {
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)->get('/admin/catalogues/amount/create');

        $response->assertStatus(403);
    }

    public function test_manager_cannot_generate_amounts(): void
    {
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)->get('/admin/catalogues/amounts/generate');

        $response->assertStatus(403);
    }

    public function test_manager_cannot_clean_amounts(): void
    {
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)->get('/admin/catalogues/amounts/clean');

        $response->assertStatus(403);
    }

    public function test_manager_cannot_edit_protected_amount(): void
    {
        $manager = $this->createUser($this->managerRole);

        $amount = Amount::create([
            'id' => 100,
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'receipt_type_id' => $this->receiptType->id,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($manager)->get('/admin/catalogues/amount/edit/' . $amount->id);

        $response->assertStatus(403);
    }

    public function test_manager_cannot_update_protected_amount(): void
    {
        $manager = $this->createUser($this->managerRole);

        $amount = Amount::create([
            'id' => 100,
            'crew_id' => $this->crew->id,
            'course_id' => $this->course->id,
            'receipt_type_id' => $this->receiptType->id,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($manager)
            ->put('/admin/catalogues/amount/update/' . $amount->id, [
                'amount' => '9999.00',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('amounts', [
            'id' => $amount->id,
            'amount' => 1000,
        ]);
    }

    public function test_edit_nonexistent_amount_returns_404(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/catalogues/amount/edit/99999');

        $response->assertStatus(404);
    }

    public function test_update_nonexistent_amount_returns_404(): void
    {
        $response = $this->actingAs($this->admin)
            ->put('/admin/catalogues/amount/update/99999', [
                'amount' => '100.00',
            ]);

        $response->assertStatus(404);
    }

    public function test_clean_amounts_removes_non_inscription_non_general(): void
    {
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5551111',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);

        $otherType = ReceiptType::create(['name' => 'Colegiatura']);

        $shouldKeep = Amount::create([
            'crew_id' => 1,
            'course_id' => $this->course->id,
            'receipt_type_id' => $this->receiptType->id,
            'amount' => 1000,
        ]);

        $shouldDelete = Amount::create([
            'crew_id' => $otherCrew->id,
            'course_id' => $this->course->id,
            'receipt_type_id' => $otherType->id,
            'amount' => 500,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/catalogues/amounts/clean');

        $response->assertRedirect(route('admin.catalogues.amounts.show'));
        $this->assertDatabaseHas('amounts', ['id' => $shouldKeep->id]);
        $this->assertDatabaseMissing('amounts', ['id' => $shouldDelete->id]);
    }
}
