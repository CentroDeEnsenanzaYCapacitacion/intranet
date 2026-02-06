<?php

namespace Tests\Feature\Admin;

use App\Models\AdjustmentDefinition;
use App\Models\Crew;
use App\Models\Department;
use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffAdjustment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $managerRole;
    private Role $staffRole;
    private Role $directorRole;
    private Crew $crew;
    private Department $department;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        $this->managerRole = Role::firstOrCreate(['id' => 2], ['name' => 'manager']);
        $this->staffRole = Role::firstOrCreate(['id' => 3], ['name' => 'staff']);
        $this->directorRole = Role::firstOrCreate(['id' => 4], ['name' => 'director']);

        $this->crew = Crew::create([
            'name' => 'Plantel Test',
            'adress' => 'Direccion Test',
            'phone' => '5550000',
            'mail' => 'plantel@test.com',
            'is_active' => true,
        ]);

        $this->department = Department::create([
            'name' => 'Departamento Test',
            'is_active' => true,
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

    private function createStaff(array $overrides = []): Staff
    {
        $data = array_merge([
            'name' => 'Empleado',
            'surnames' => 'Test',
            'crew_id' => $this->crew->id,
            'isActive' => true,
        ], $overrides);

        return Staff::create($data);
    }

    public function test_admin_can_view_staff_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/staff/');

        $response->assertStatus(200);
        $response->assertViewIs('admin.rosters.staff.show');
    }

    public function test_manager_can_view_staff_list(): void
    {
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)->get('/admin/staff/');

        $response->assertStatus(200);
    }

    public function test_director_can_view_staff_list(): void
    {
        $director = $this->createUser($this->directorRole);

        $response = $this->actingAs($director)->get('/admin/staff/');

        $response->assertStatus(200);
    }

    public function test_staff_cannot_view_staff_list(): void
    {
        $staff = $this->createUser($this->staffRole);

        $response = $this->actingAs($staff)->get('/admin/staff/');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/staff/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.rosters.staff.new');
    }

    public function test_admin_can_create_staff(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/staff/store', [
                'name' => 'Nuevo Empleado',
                'surnames' => 'Apellidos',
                'crew_id' => $this->crew->id,
            ]);

        $response->assertRedirect(route('admin.staff.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('staff', [
            'name' => 'Nuevo Empleado',
            'surnames' => 'Apellidos',
            'isActive' => true,
        ]);
    }

    public function test_create_validates_required_name(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/staff/store', [
                'surnames' => 'Apellidos',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_view_edit_form(): void
    {
        $staff = $this->createStaff();

        $response = $this->actingAs($this->admin)->get('/admin/staff/' . $staff->id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('admin.rosters.staff.edit');
    }

    public function test_admin_can_update_staff(): void
    {
        $staff = $this->createStaff();

        $response = $this->actingAs($this->admin)
            ->put('/admin/staff/' . $staff->id, [
                'Address' => 'Nueva Direccion',
                'phone' => '5559999',
            ]);

        $response->assertRedirect(route('admin.staff.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('staff', [
            'id' => $staff->id,
            'Address' => 'Nueva Direccion',
            'phone' => '5559999',
        ]);
    }

    public function test_admin_can_deactivate_staff(): void
    {
        $staff = $this->createStaff();

        $response = $this->actingAs($this->admin)
            ->patch('/admin/staff/' . $staff->id . '/deactivate');

        $response->assertRedirect(route('admin.staff.show'));
        $this->assertDatabaseHas('staff', [
            'id' => $staff->id,
            'isActive' => false,
        ]);
    }

    public function test_manager_cannot_edit_other_crew_staff(): void
    {
        $managerCrew = Crew::create([
            'name' => 'Plantel Manager',
            'adress' => 'Direccion Manager',
            'phone' => '5552222',
            'mail' => 'manager@test.com',
            'is_active' => true,
        ]);
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5551111',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);
        $manager = $this->createUser($this->managerRole, ['crew_id' => $managerCrew->id]);
        $staff = $this->createStaff(['crew_id' => $otherCrew->id]);

        $response = $this->actingAs($manager)
            ->get('/admin/staff/' . $staff->id . '/edit');

        $response->assertStatus(403);
    }

    public function test_manager_cannot_deactivate_other_crew_staff(): void
    {
        $managerCrew = Crew::create([
            'name' => 'Plantel Manager',
            'adress' => 'Direccion Manager',
            'phone' => '5552222',
            'mail' => 'manager@test.com',
            'is_active' => true,
        ]);
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5551111',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);
        $manager = $this->createUser($this->managerRole, ['crew_id' => $managerCrew->id]);
        $staff = $this->createStaff(['crew_id' => $otherCrew->id]);

        $response = $this->actingAs($manager)
            ->patch('/admin/staff/' . $staff->id . '/deactivate');

        $response->assertStatus(403);
    }

    public function test_admin_can_add_adjustment(): void
    {
        $staff = $this->createStaff();
        $definition = AdjustmentDefinition::create([
            'name' => 'Bono Test',
            'type' => 'perception',
        ]);

        $response = $this->actingAs($this->admin)
            ->post('/admin/staff-adjustments', [
                'staff_id' => $staff->id,
                'definition_id' => $definition->id,
                'amount' => 500,
                'year' => 2026,
                'month' => 1,
                'period' => '1-15',
                'crew_id' => $this->crew->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('staff_adjustments', [
            'staff_id' => $staff->id,
            'adjustment_definition_id' => $definition->id,
            'amount' => 500,
        ]);
    }

    public function test_add_adjustment_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/staff-adjustments', []);

        $response->assertSessionHasErrors(['staff_id', 'definition_id', 'amount', 'year', 'month', 'period', 'crew_id']);
    }

    public function test_admin_can_delete_adjustment(): void
    {
        $staff = $this->createStaff();
        $definition = AdjustmentDefinition::create([
            'name' => 'Bono Test',
            'type' => 'perception',
        ]);
        $adjustment = StaffAdjustment::create([
            'staff_id' => $staff->id,
            'adjustment_definition_id' => $definition->id,
            'amount' => 500,
            'year' => 2026,
            'month' => 1,
            'period' => '1-15',
            'crew_id' => $this->crew->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete('/admin/staff-adjustments/' . $adjustment->id);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('staff_adjustments', [
            'id' => $adjustment->id,
        ]);
    }

    public function test_manager_cannot_delete_other_crew_adjustment(): void
    {
        $managerCrew = Crew::create([
            'name' => 'Plantel Manager',
            'adress' => 'Direccion Manager',
            'phone' => '5552222',
            'mail' => 'manager@test.com',
            'is_active' => true,
        ]);
        $otherCrew = Crew::create([
            'name' => 'Otro Plantel',
            'adress' => 'Otra Direccion',
            'phone' => '5551111',
            'mail' => 'otro@test.com',
            'is_active' => true,
        ]);
        $manager = $this->createUser($this->managerRole, ['crew_id' => $managerCrew->id]);
        $staff = $this->createStaff(['crew_id' => $otherCrew->id]);
        $definition = AdjustmentDefinition::create([
            'name' => 'Bono Test',
            'type' => 'perception',
        ]);
        $adjustment = StaffAdjustment::create([
            'staff_id' => $staff->id,
            'adjustment_definition_id' => $definition->id,
            'amount' => 500,
            'year' => 2026,
            'month' => 1,
            'period' => '1-15',
            'crew_id' => $otherCrew->id,
        ]);

        $response = $this->actingAs($manager)
            ->delete('/admin/staff-adjustments/' . $adjustment->id);

        $response->assertStatus(403);
    }

    public function test_admin_can_view_rosters_panel(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/rosters/');

        $response->assertStatus(200);
        $response->assertViewIs('admin.rosters.rosters.panel');
    }

    public function test_guest_cannot_access_staff(): void
    {
        $response = $this->get('/admin/staff/');

        $response->assertRedirect(route('login'));
    }
}
