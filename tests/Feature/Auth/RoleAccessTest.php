<?php

namespace Tests\Feature\Auth;

use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private Role $allowedRole;
    private Role $deniedRole;
    private Crew $crew;

    protected function setUp(): void
    {
        parent::setUp();

        $this->allowedRole = Role::firstOrCreate(
            ['id' => 1],
            ['name' => 'Admin']
        );

        $this->deniedRole = Role::firstOrCreate(
            ['id' => 3],
            ['name' => 'Staff']
        );

        $this->crew = Crew::create([
            'name' => 'Main',
            'adress' => 'Address',
            'phone' => '5550000',
            'mail' => 'crew@example.com',
        ]);
    }

    private function createUser(int $roleId, array $overrides = []): User
    {
        $data = array_merge([
            'name' => 'Test',
            'surnames' => 'User',
            'username' => 'user_' . uniqid(),
            'role_id' => $roleId,
            'crew_id' => $this->crew->id,
            'genre' => 'M',
            'email' => 'user_' . uniqid() . '@example.com',
            'password' => Hash::make('secret123'),
            'is_active' => true,
        ], $overrides);

        return User::create($data);
    }

    public function test_allowed_role_can_access_calendar_menu(): void
    {
        $user = $this->createUser($this->allowedRole->id);

        $response = $this->actingAs($user)->get('/system/calendars/menu');

        $response->assertStatus(200);
    }

    public function test_denied_role_is_forbidden_from_calendar_menu(): void
    {
        $user = $this->createUser($this->deniedRole->id);

        $response = $this->actingAs($user)->get('/system/calendars/menu');

        $response->assertStatus(403);
    }

    public function test_inactive_user_is_redirected_from_role_protected_route(): void
    {
        $user = $this->createUser($this->allowedRole->id, ['is_active' => false]);

        $response = $this->actingAs($user)->get('/system/calendars/menu');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Tu cuenta no está activa.');
        $this->assertGuest();
    }

    public function test_password_confirmation_is_required_for_sensitive_route(): void
    {
        $admin = $this->createUser($this->allowedRole->id);
        $target = $this->createUser($this->deniedRole->id, ['is_active' => false]);

        $response = $this->actingAs($admin)->get('/admin/user/activate/' . $target->id);

        $response->assertRedirect(route('password.confirm'));
    }

    public function test_password_confirmation_allows_sensitive_route(): void
    {
        $admin = $this->createUser($this->allowedRole->id);
        $target = $this->createUser($this->deniedRole->id, ['is_active' => false]);

        $response = $this->actingAs($admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get('/admin/user/activate/' . $target->id);

        $response->assertRedirect(route('admin.users.show'));
        $this->assertTrue((bool) $target->fresh()->is_active);
    }
}
