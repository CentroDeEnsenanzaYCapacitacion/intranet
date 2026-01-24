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

    public function test_password_confirmation_expires_after_timeout(): void
    {
        config(['auth.password_timeout' => 1]);

        $target = User::create([
            'name' => 'Target',
            'surnames' => 'User',
            'username' => 'target_' . uniqid(),
            'role_id' => $this->adminRole->id,
            'crew_id' => $this->crew->id,
            'genre' => 'M',
            'email' => 'target_' . uniqid() . '@capacitacioncec.edu.mx',
            'password' => Hash::make('SecretPassw0rd!'),
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time() - 10])
            ->get('/admin/user/activate/' . $target->id);

        $response->assertRedirect(route('password.confirm'));
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
}
