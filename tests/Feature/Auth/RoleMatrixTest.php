<?php

namespace Tests\Feature\Auth;

use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleMatrixTest extends TestCase
{
    use RefreshDatabase;

    private Crew $crew;
    private array $roles = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->roles = [
            'admin' => $this->createRole(1, 'admin'),
            'manager' => $this->createRole(2, 'manager'),
            'staff' => $this->createRole(3, 'staff'),
            'role4' => $this->createRole(4, 'role4'),
            'role5' => $this->createRole(5, 'role5'),
            'role7' => $this->createRole(7, 'role7'),
        ];

        $this->crew = Crew::create([
            'name' => 'Main',
            'adress' => 'Address',
            'phone' => '5550000',
            'mail' => 'crew@example.com',
        ]);
    }

    private function createRole(int $id, string $name): Role
    {
        return Role::firstOrCreate(
            ['id' => $id],
            ['name' => $name]
        );
    }

    private function createUser(Role $role): User
    {
        return User::create([
            'name' => 'User',
            'surnames' => 'Test',
            'username' => 'user_' . uniqid(),
            'role_id' => $role->id,
            'crew_id' => $this->crew->id,
            'genre' => 'M',
            'email' => 'user_' . uniqid() . '@capacitacioncec.edu.mx',
            'password' => Hash::make('SecretPassw0rd!'),
            'is_active' => true,
        ]);
    }

    public function test_collection_menu_allows_admin_role(): void
    {
        $user = $this->createUser($this->roles['admin']);

        $response = $this->actingAs($user)->get('/system/collection/menu');

        $response->assertStatus(200);
    }

    public function test_collection_menu_denies_unlisted_role(): void
    {
        $user = $this->createUser($this->roles['role4']);

        $response = $this->actingAs($user)->get('/system/collection/menu');

        $response->assertStatus(403);
    }

    public function test_stats_reports_allows_manager_role(): void
    {
        $user = $this->createUser($this->roles['manager']);

        $response = $this->actingAs($user)->get('/admin/stats/reports/anual/2025');

        $response->assertStatus(200);
    }

    public function test_stats_reports_denies_staff_role(): void
    {
        $user = $this->createUser($this->roles['staff']);

        $response = $this->actingAs($user)->get('/admin/stats/reports/anual/2025');

        $response->assertStatus(403);
    }

    public function test_admin_requests_allows_role_5(): void
    {
        $user = $this->createUser($this->roles['role5']);

        $response = $this->actingAs($user)->get('/admin/requests');

        $response->assertStatus(200);
    }

    public function test_admin_requests_denies_role_4(): void
    {
        $user = $this->createUser($this->roles['role4']);

        $response = $this->actingAs($user)->get('/admin/requests');

        $response->assertStatus(403);
    }

    public function test_grades_menu_allows_role_7(): void
    {
        $user = $this->createUser($this->roles['role7']);

        $response = $this->actingAs($user)->get('/system/grades/menu');

        $response->assertStatus(200);
    }

    public function test_grades_menu_denies_staff_role(): void
    {
        $user = $this->createUser($this->roles['staff']);

        $response = $this->actingAs($user)->get('/system/grades/menu');

        $response->assertStatus(403);
    }

    public function test_student_search_allows_staff_role(): void
    {
        $user = $this->createUser($this->roles['staff']);

        $response = $this->actingAs($user)->get('/system/student/search');

        $response->assertStatus(200);
    }

    public function test_student_search_denies_unlisted_role(): void
    {
        $user = $this->createUser($this->roles['role4']);

        $response = $this->actingAs($user)->get('/system/student/search');

        $response->assertStatus(403);
    }
}
