<?php

namespace Tests\Feature\Auth;

use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
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

    public function test_change_password_rejects_incorrect_current_password(): void
    {
        $response = $this->actingAs($this->user)->from('/change-password')->post('/change-password', [
            'current_password' => 'wrong-pass',
            'password' => 'NewPassw0rd!',
            'password_confirmation' => 'NewPassw0rd!',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('OldPassw0rd!', $this->user->fresh()->password));
    }

    public function test_change_password_validates_strength(): void
    {
        $response = $this->actingAs($this->user)->from('/change-password')->post('/change-password', [
            'current_password' => 'OldPassw0rd!',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('OldPassw0rd!', $this->user->fresh()->password));
    }

    public function test_change_password_updates_password(): void
    {
        $response = $this->actingAs($this->user)->from('/change-password')->post('/change-password', [
            'current_password' => 'OldPassw0rd!',
            'password' => 'NewPassw0rd!',
            'password_confirmation' => 'NewPassw0rd!',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewPassw0rd!', $this->user->fresh()->password));
    }
}
