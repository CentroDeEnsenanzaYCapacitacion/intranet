<?php

namespace Tests\Feature\Auth;

use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SetPasswordTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->disableUncompromised();

        $role = Role::firstOrCreate(
            ['id' => 1],
            ['name' => 'admin']
        );

        $crew = Crew::create([
            'name' => 'Main',
            'adress' => 'Address',
            'phone' => '5550000',
            'mail' => 'crew@example.com',
        ]);

        $this->user = User::create([
            'name' => 'User',
            'surnames' => 'Test',
            'username' => 'user_' . uniqid(),
            'role_id' => $role->id,
            'crew_id' => $crew->id,
            'genre' => 'M',
            'email' => 'user_' . uniqid() . '@capacitacioncec.edu.mx',
            'password' => Hash::make('OldPassw0rd!'),
            'is_active' => true,
        ]);
    }

    private function disableUncompromised(): void
    {
        $this->app->instance(UncompromisedVerifier::class, new class implements UncompromisedVerifier {
            public function verify($data)
            {
                return true;
            }
        });
    }

    public function test_set_password_with_valid_token_updates_password_and_marks_invitation_used(): void
    {
        $token = 'token_' . uniqid();
        $invitation = UserInvitation::create([
            'user_id' => $this->user->id,
            'token' => $token,
            'expires_at' => now()->addDay(),
            'used' => false,
        ]);

        $response = $this->post('/set-password', [
            'token' => $token,
            'password' => 'NewPassw0rd!',
            'password_confirmation' => 'NewPassw0rd!',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('NewPassw0rd!', $this->user->fresh()->password));
        $this->assertTrue($invitation->fresh()->used);
    }

    public function test_set_password_rejects_invalid_token(): void
    {
        $response = $this->post('/set-password', [
            'token' => 'invalid',
            'password' => 'NewPassw0rd!',
            'password_confirmation' => 'NewPassw0rd!',
        ]);

        $response->assertSessionHas('error');
        $this->assertTrue(Hash::check('OldPassw0rd!', $this->user->fresh()->password));
    }

    public function test_set_password_rejects_expired_token(): void
    {
        $token = 'token_' . uniqid();
        $invitation = UserInvitation::create([
            'user_id' => $this->user->id,
            'token' => $token,
            'expires_at' => now()->subMinute(),
            'used' => false,
        ]);

        $response = $this->post('/set-password', [
            'token' => $token,
            'password' => 'NewPassw0rd!',
            'password_confirmation' => 'NewPassw0rd!',
        ]);

        $response->assertSessionHas('error');
        $this->assertFalse($invitation->fresh()->used);
        $this->assertTrue(Hash::check('OldPassw0rd!', $this->user->fresh()->password));
    }

    public function test_set_password_form_rejects_invalid_token(): void
    {
        $response = $this->get('/set-password?token=invalid');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }
}
