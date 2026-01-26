<?php

namespace Tests\Feature\Auth;

use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private Role $role;
    private Crew $crew;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = Role::firstOrCreate(
            ['id' => 1],
            ['name' => 'Admin']
        );

        $this->crew = Crew::create([
            'name' => 'Main',
            'adress' => 'Address',
            'phone' => '5550000',
            'mail' => 'crew@example.com',
        ]);
    }

    private function createUser(array $overrides = []): User
    {
        $rawPassword = $overrides['raw_password'] ?? 'secret123';

        $data = array_merge([
            'name' => 'Test',
            'surnames' => 'User',
            'username' => 'user_' . uniqid(),
            'role_id' => $this->role->id,
            'crew_id' => $this->crew->id,
            'genre' => 'M',
            'email' => 'user_' . uniqid() . '@example.com',
            'password' => Hash::make($rawPassword),
            'is_active' => true,
        ], $overrides);

        unset($data['raw_password']);

        return User::create($data);
    }

    public function test_login_page_is_available(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $password = 'secret123';
        $user = $this->createUser(['raw_password' => $password]);
        $beforeLogin = now()->subSeconds(1);

        $response = $this->post('/', [
            'username' => $user->username,
            'password' => $password,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->last_login);
        $this->assertTrue($user->fresh()->last_login->greaterThan($beforeLogin));
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = $this->createUser(['raw_password' => 'secret123']);

        $response = $this->post('/', [
            'username' => $user->username,
            'password' => 'wrong-pass',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Credenciales incorrectas.');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $password = 'secret123';
        $user = $this->createUser([
            'raw_password' => $password,
            'is_active' => false,
        ]);

        $response = $this->post('/', [
            'username' => $user->username,
            'password' => $password,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_too_many_attempts(): void
    {
        $username = 'ratelimit_' . uniqid();
        $payload = [
            'username' => $username,
            'password' => 'wrong-pass',
        ];
        $ip = '192.0.2.10';

        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => $ip])->post('/', $payload);
        }

        $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])->post('/', $payload);

        $response->assertStatus(429);
        $this->assertGuest();
    }
}
