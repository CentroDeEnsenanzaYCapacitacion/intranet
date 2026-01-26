<?php

namespace Tests\Feature\Auth;

use App\Mail\ResetPassword;
use App\Models\Crew;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
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

    public function test_send_reset_link_sends_mail_and_creates_token_for_active_user(): void
    {
        Mail::fake();

        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => $this->user->email,
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(PasswordReset::where('email', $this->user->email)->exists());

        Mail::assertSent(ResetPassword::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });
    }

    public function test_send_reset_link_fails_for_inactive_user(): void
    {
        Mail::fake();

        $this->user->update(['is_active' => false]);

        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => $this->user->email,
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(PasswordReset::where('email', $this->user->email)->exists());
        Mail::assertNothingSent();
    }

    public function test_reset_password_rejects_invalid_token(): void
    {
        $token = 'token_' . uniqid();

        PasswordReset::create([
            'email' => $this->user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $response = $this->from('/reset-password/' . $token)->post('/reset-password', [
            'token' => 'invalid',
            'email' => $this->user->email,
            'password' => 'NewPassw0rd!',
            'password_confirmation' => 'NewPassw0rd!',
        ]);

        $response->assertSessionHasErrors('token');
        $this->assertTrue(Hash::check('OldPassw0rd!', $this->user->fresh()->password));
    }

    public function test_reset_password_rejects_expired_token(): void
    {
        $token = 'token_' . uniqid();

        PasswordReset::create([
            'email' => $this->user->email,
            'token' => Hash::make($token),
            'created_at' => now()->subMinutes(61),
        ]);

        $response = $this->from('/reset-password/' . $token)->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'NewPassw0rd!',
            'password_confirmation' => 'NewPassw0rd!',
        ]);

        $response->assertSessionHasErrors('token');
        $this->assertFalse(PasswordReset::where('email', $this->user->email)->exists());
        $this->assertTrue(Hash::check('OldPassw0rd!', $this->user->fresh()->password));
    }

    public function test_reset_password_updates_password_and_clears_token(): void
    {
        $token = 'token_' . uniqid();

        PasswordReset::create([
            'email' => $this->user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $invitation = UserInvitation::create([
            'user_id' => $this->user->id,
            'token' => 'inv_' . uniqid(),
            'expires_at' => now()->addDay(),
            'used' => false,
        ]);

        $response = $this->from('/reset-password/' . $token)->post('/reset-password', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'NewPassw0rd!',
            'password_confirmation' => 'NewPassw0rd!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertFalse(PasswordReset::where('email', $this->user->email)->exists());
        $this->assertTrue(Hash::check('NewPassw0rd!', $this->user->fresh()->password));
        $this->assertTrue($invitation->fresh()->used);
    }
}
