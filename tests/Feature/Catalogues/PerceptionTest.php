<?php

namespace Tests\Feature\Catalogues;

use App\Models\AdjustmentDefinition;
use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PerceptionTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $managerRole;
    private Role $staffRole;
    private Role $directorRole;
    private Crew $crew;
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

    public function test_admin_can_view_perceptions_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/catalogues/defs');

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.perceptions.show');
        $response->assertViewHas(['perceptions', 'deductions']);
    }

    public function test_manager_can_view_perceptions_list(): void
    {
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)->get('/admin/catalogues/defs');

        $response->assertStatus(200);
    }

    public function test_director_can_view_perceptions_list(): void
    {
        $director = $this->createUser($this->directorRole);

        $response = $this->actingAs($director)->get('/admin/catalogues/defs');

        $response->assertStatus(200);
    }

    public function test_staff_cannot_view_perceptions_list(): void
    {
        $staff = $this->createUser($this->staffRole);

        $response = $this->actingAs($staff)->get('/admin/catalogues/defs');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_perception(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/catalogues/defs', [
                'name' => 'Bono de productividad',
                'type' => 'perception',
            ]);

        $response->assertRedirect(route('admin.catalogues.perceptions.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('adjustment_definitions', [
            'name' => 'Bono de productividad',
            'type' => 'perception',
        ]);
    }

    public function test_admin_can_create_deduction(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/catalogues/defs', [
                'name' => 'IMSS',
                'type' => 'deduction',
            ]);

        $response->assertRedirect(route('admin.catalogues.perceptions.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('adjustment_definitions', [
            'name' => 'IMSS',
            'type' => 'deduction',
        ]);
    }

    public function test_create_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/catalogues/defs', []);

        $response->assertSessionHasErrors(['name', 'type']);
    }

    public function test_create_validates_type_values(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/catalogues/defs', [
                'name' => 'Test',
                'type' => 'invalid_type',
            ]);

        $response->assertSessionHasErrors('type');
    }

    public function test_create_validates_name_max_length(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/catalogues/defs', [
                'name' => str_repeat('a', 256),
                'type' => 'perception',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_delete_perception(): void
    {
        $perception = AdjustmentDefinition::create([
            'name' => 'Percepcion a eliminar',
            'type' => 'perception',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete('/admin/catalogues/defs/' . $perception->id);

        $response->assertRedirect(route('admin.catalogues.perceptions.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('adjustment_definitions', [
            'id' => $perception->id,
        ]);
    }

    public function test_admin_can_delete_deduction(): void
    {
        $deduction = AdjustmentDefinition::create([
            'name' => 'Deduccion a eliminar',
            'type' => 'deduction',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete('/admin/catalogues/defs/' . $deduction->id);

        $response->assertRedirect(route('admin.catalogues.perceptions.show'));
        $this->assertDatabaseMissing('adjustment_definitions', [
            'id' => $deduction->id,
        ]);
    }

    public function test_perceptions_and_deductions_are_separated_in_view(): void
    {
        AdjustmentDefinition::create(['name' => 'Bono', 'type' => 'perception']);
        AdjustmentDefinition::create(['name' => 'ISR', 'type' => 'deduction']);

        $response = $this->actingAs($this->admin)->get('/admin/catalogues/defs');

        $response->assertStatus(200);
        $perceptions = $response->viewData('perceptions');
        $deductions = $response->viewData('deductions');

        $this->assertEquals(1, $perceptions->count());
        $this->assertEquals(1, $deductions->count());
        $this->assertEquals('Bono', $perceptions->first()->name);
        $this->assertEquals('ISR', $deductions->first()->name);
    }

    public function test_guest_cannot_access_perceptions(): void
    {
        $response = $this->get('/admin/catalogues/defs');

        $response->assertRedirect(route('login'));
    }
}
