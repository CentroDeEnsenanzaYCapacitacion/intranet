<?php

namespace Tests\Feature\Admin;

use App\Mail\UserInvitation;
use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInvitation as UserInvitationModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $managerRole;
    private Role $staffRole;
    private Crew $crew;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(
            ['id' => 1],
            ['name' => 'admin']
        );

        $this->managerRole = Role::firstOrCreate(
            ['id' => 2],
            ['name' => 'manager']
        );

        $this->staffRole = Role::firstOrCreate(
            ['id' => 3],
            ['name' => 'staff']
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

    private function createUser(int $roleId, array $overrides = []): User
    {
        $data = array_merge([
            'name' => 'User',
            'surnames' => 'Test',
            'username' => 'user_' . uniqid(),
            'role_id' => $roleId,
            'crew_id' => $this->crew->id,
            'genre' => 'M',
            'email' => 'user_' . uniqid() . '@capacitacioncec.edu.mx',
            'password' => Hash::make('SecretPassw0rd!'),
            'is_active' => true,
        ], $overrides);

        return User::create($data);
    }

    public function test_admin_can_create_user_and_invitation(): void
    {
        Mail::fake();

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/admin/user/insert', [
                'name' => 'Nuevo',
                'surnames' => 'Usuario',
                'email' => 'nuevo_' . uniqid() . '@capacitacioncec.edu.mx',
                'role_id' => $this->managerRole->id,
                'crew_id' => $this->crew->id,
                'phone' => '1234567890',
                'cel_phone' => '1234567890',
                'genre' => 'M',
            ]);

        $response->assertRedirect(route('admin.users.show'));

        $created = User::where('role_id', $this->managerRole->id)->latest('id')->first();
        $this->assertNotNull($created);
        $this->assertTrue(UserInvitationModel::where('user_id', $created->id)->exists());
        Mail::assertSent(UserInvitation::class);
    }

    public function test_admin_can_update_user(): void
    {
        $user = $this->createUser($this->managerRole->id);

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->put('/admin/user/update/' . $user->id, [
                'name' => 'Actualizado',
                'surnames' => 'Usuario',
                'email' => 'actualizado_' . uniqid() . '@capacitacioncec.edu.mx',
                'role_id' => $this->managerRole->id,
                'crew_id' => $this->crew->id,
                'phone' => '1234567890',
                'cel_phone' => '1234567890',
                'genre' => 'M',
            ]);

        $response->assertRedirect(route('admin.users.show'));
        $this->assertSame('Actualizado', $user->fresh()->name);
    }

    public function test_admin_can_block_user(): void
    {
        $user = $this->createUser($this->managerRole->id);

        UserInvitationModel::create([
            'user_id' => $user->id,
            'token' => 'tok_' . uniqid(),
            'expires_at' => now()->addDay(),
            'used' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->delete('/admin/user/block/' . $user->id);

        $response->assertRedirect(route('admin.users.show'));
        $this->assertSame(0, (int) $user->fresh()->is_active);
        $this->assertFalse(UserInvitationModel::where('user_id', $user->id)->where('used', false)->exists());
    }

    public function test_admin_can_activate_user_and_send_invitation(): void
    {
        Mail::fake();

        $user = $this->createUser($this->managerRole->id, ['is_active' => false]);

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get('/admin/user/activate/' . $user->id);

        $response->assertRedirect(route('admin.users.show'));
        $this->assertSame(1, (int) $user->fresh()->is_active);
        $this->assertTrue(UserInvitationModel::where('user_id', $user->id)->exists());
        Mail::assertSent(UserInvitation::class);
    }

    public function test_non_admin_cannot_access_admin_users_routes(): void
    {
        $user = $this->createUser($this->staffRole->id);

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_resend_invitation(): void
    {
        Mail::fake();

        $user = $this->createUser($this->managerRole->id);

        $oldToken = 'old_' . uniqid();
        UserInvitationModel::create([
            'user_id' => $user->id,
            'token' => $oldToken,
            'expires_at' => now()->addDays(3),
            'used' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/admin/user/resend-invitation/' . $user->id);

        $response->assertRedirect(route('admin.users.show'));
        $response->assertSessionHas('success', 'Invitación reenviada exitosamente');

        $this->assertFalse(UserInvitationModel::where('token', $oldToken)->exists());
        $this->assertSame(1, UserInvitationModel::where('user_id', $user->id)->where('used', false)->count());
        Mail::assertSent(UserInvitation::class);
    }

    public function test_staff_cannot_resend_invitation(): void
    {
        $user = $this->createUser($this->managerRole->id);
        $staff = $this->createUser($this->staffRole->id);

        $response = $this->actingAs($staff)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->post('/admin/user/resend-invitation/' . $user->id);

        $response->assertStatus(403);
    }
}
