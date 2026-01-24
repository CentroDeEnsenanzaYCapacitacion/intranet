<?php

namespace Tests\Feature\Tickets;

use App\Models\Crew;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketImage;
use App\Models\TicketMessage;
use App\Models\TicketMessageAttachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketFlowTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $staffRole;
    private Crew $crew;
    private TicketCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(
            ['id' => 1],
            ['name' => 'admin']
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

        $this->category = TicketCategory::create([
            'name' => 'General',
        ]);
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

    private function createTicket(User $user, array $overrides = []): Ticket
    {
        $data = array_merge([
            'title' => 'Ticket',
            'description' => 'Descripcion',
            'priority' => 'media',
            'category_id' => $this->category->id,
            'user_id' => $user->id,
            'status' => 'abierto',
        ], $overrides);

        return Ticket::create($data);
    }

    public function test_user_can_create_ticket(): void
    {
        Storage::fake();

        $user = $this->createUser($this->staffRole);

        $response = $this->actingAs($user)->post('/tickets/save', [
            'title' => 'Nuevo ticket',
            'description' => 'Detalle',
            'priority' => 'media',
            'category_id' => $this->category->id,
            'images' => [
                UploadedFile::fake()->image('evidencia.jpg'),
            ],
        ]);

        $response->assertRedirect(route('tickets.list'));
        $this->assertDatabaseHas('tickets', [
            'title' => 'Nuevo ticket',
            'user_id' => $user->id,
            'status' => 'abierto',
        ]);

        $image = TicketImage::first();
        $this->assertNotNull($image);
        Storage::assertExists('tickets/' . $image->path);
    }

    public function test_user_can_add_message_to_open_ticket(): void
    {
        Storage::fake();

        $user = $this->createUser($this->staffRole);
        $ticket = $this->createTicket($user);

        $response = $this->actingAs($user)->post('/tickets/' . $ticket->id . '/message', [
            'message' => 'Mensaje de prueba',
            'attachments' => [
                UploadedFile::fake()->create('video.mp4', 10, 'video/mp4'),
            ],
        ]);

        $response->assertSessionHas('success', 'Mensaje enviado.');
        $this->assertDatabaseHas('ticket_messages', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'Mensaje de prueba',
        ]);

        $message = TicketMessage::first();
        $attachment = TicketMessageAttachment::first();
        $this->assertNotNull($message);
        $this->assertNotNull($attachment);
        $this->assertSame($message->id, $attachment->ticket_message_id);
        Storage::assertExists('tickets/' . $attachment->path);
    }

    public function test_non_admin_cannot_add_message_to_closed_ticket(): void
    {
        $user = $this->createUser($this->staffRole);
        $ticket = $this->createTicket($user, ['status' => 'cerrado']);

        $response = $this->actingAs($user)->post('/tickets/' . $ticket->id . '/message', [
            'message' => 'Mensaje',
        ]);

        $response->assertSessionHas('error', 'No puedes añadir mensajes a un ticket cerrado o resuelto.');
        $this->assertDatabaseMissing('ticket_messages', [
            'ticket_id' => $ticket->id,
            'message' => 'Mensaje',
        ]);
    }

    public function test_non_admin_cannot_update_status_to_cerrado(): void
    {
        $user = $this->createUser($this->staffRole);
        $ticket = $this->createTicket($user);

        $response = $this->actingAs($user)->put('/tickets/' . $ticket->id . '/status', [
            'status' => 'cerrado',
        ]);

        $response->assertSessionHas('error', 'No tienes permisos para cambiar a este estado.');
        $this->assertSame('abierto', $ticket->fresh()->status);
    }

    public function test_admin_can_close_ticket_and_sets_closed_at(): void
    {
        $admin = $this->createUser($this->adminRole);
        $ticket = $this->createTicket($admin);

        $response = $this->actingAs($admin)->put('/tickets/' . $ticket->id . '/status', [
            'status' => 'cerrado',
        ]);

        $response->assertSessionHas('success', 'Estado actualizado.');
        $ticket = $ticket->fresh();
        $this->assertSame('cerrado', $ticket->status);
        $this->assertNotNull($ticket->closed_at);
    }

    public function test_user_cannot_access_other_users_ticket_attachment(): void
    {
        $owner = $this->createUser($this->staffRole);
        $other = $this->createUser($this->staffRole);
        $ticket = $this->createTicket($owner);

        $image = TicketImage::create([
            'ticket_id' => $ticket->id,
            'path' => 'archivo_' . uniqid() . '.png',
            'original_name' => 'archivo.png',
        ]);

        $response = $this->actingAs($other)->get('/tickets/attachment/' . $image->path);

        $response->assertStatus(403);
    }

    public function test_owner_can_access_ticket_image(): void
    {
        Storage::fake();

        $owner = $this->createUser($this->staffRole);
        $ticket = $this->createTicket($owner);

        $image = TicketImage::create([
            'ticket_id' => $ticket->id,
            'path' => 'archivo_' . uniqid() . '.png',
            'original_name' => 'archivo.png',
        ]);

        Storage::put('tickets/' . $image->path, 'image-content');

        $response = $this->actingAs($owner)->get('/tickets/image/' . $image->path);

        $response->assertStatus(200);
    }

    public function test_admin_can_access_other_users_ticket_attachment(): void
    {
        Storage::fake();

        $admin = $this->createUser($this->adminRole);
        $owner = $this->createUser($this->staffRole);
        $ticket = $this->createTicket($owner);

        $image = TicketImage::create([
            'ticket_id' => $ticket->id,
            'path' => 'archivo_' . uniqid() . '.png',
            'original_name' => 'archivo.png',
        ]);

        Storage::put('tickets/' . $image->path, 'file-content');

        $response = $this->actingAs($admin)->get('/tickets/attachment/' . $image->path);

        $response->assertStatus(200);
    }
}
