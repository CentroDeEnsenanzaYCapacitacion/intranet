<?php

namespace Tests\Feature\Auth;

use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Crew $crew;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(
            ['id' => 1],
            ['name' => 'admin']
        );

        $this->crew = Crew::create([
            'name' => 'Main',
            'adress' => 'Address',
            'phone' => '5550000',
            'mail' => 'crew@example.com',
        ]);

        $this->admin = User::create([
            'name' => 'Admin',
            'surnames' => 'User',
            'username' => 'admin_' . uniqid(),
            'role_id' => $this->adminRole->id,
            'crew_id' => $this->crew->id,
            'genre' => 'M',
            'email' => 'admin_' . uniqid() . '@capacitacioncec.edu.mx',
            'password' => Hash::make('SecretPassw0rd!'),
            'is_active' => true,
        ]);
    }

    public function test_confirm_password_sets_session_and_redirects_to_intended(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['url.intended' => route('admin.users.show')])
            ->post('/confirm-password', [
                'password' => 'SecretPassw0rd!',
            ]);

        $response->assertRedirect(route('admin.users.show'));
        $response->assertSessionHas('auth.password_confirmed_at');
    }

    public function test_confirm_password_rejects_wrong_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/confirm-password', [
                'password' => 'WrongPassword!',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_confirm_password_requires_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/confirm-password', []);

        $response->assertSessionHasErrors('password');
    }

    public function test_confirm_password_ajax_returns_json_success(): void
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/confirm-password/ajax', [
                'password' => 'SecretPassw0rd!',
            ]);

        $response->assertOk();
        $response->assertJson(['confirmed' => true]);
        $response->assertSessionHas('auth.password_confirmed_at');
    }

    public function test_confirm_password_ajax_rejects_wrong_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/confirm-password/ajax', [
                'password' => 'WrongPassword!',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    public function test_confirm_password_ajax_requires_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/confirm-password/ajax', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }

    public function test_confirm_password_ajax_requires_authentication(): void
    {
        $response = $this->postJson('/confirm-password/ajax', [
            'password' => 'SecretPassw0rd!',
        ]);

        $response->assertStatus(401);
    }

    public function test_confirm_password_page_is_available(): void
    {
        $response = $this->actingAs($this->admin)->get('/confirm-password');

        $response->assertStatus(200);
        $response->assertViewIs('auth.confirm-password');
    }
}
