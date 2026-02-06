<?php

namespace Tests\Feature\Catalogues;

use App\Models\Course;
use App\Models\Crew;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CourseTest extends TestCase
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

        $this->adminRole = Role::firstOrCreate(['id' => 1], ['name' => 'admin']);
        $this->managerRole = Role::firstOrCreate(['id' => 2], ['name' => 'manager']);
        $this->staffRole = Role::firstOrCreate(['id' => 3], ['name' => 'staff']);

        $this->crew = Crew::create([
            'name' => 'Plantel Test',
            'adress' => 'Direccion Test',
            'phone' => '5550000',
            'mail' => 'plantel@test.com',
            'is_active' => true,
        ]);

        $this->admin = $this->createUser($this->adminRole);
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

    public function test_admin_can_view_courses_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/catalogues/courses');

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.courses.show');
    }

    public function test_manager_can_view_courses_list(): void
    {
        $manager = $this->createUser($this->managerRole);

        $response = $this->actingAs($manager)->get('/admin/catalogues/courses');

        $response->assertStatus(200);
    }

    public function test_staff_cannot_view_courses_list(): void
    {
        $staff = $this->createUser($this->staffRole);

        $response = $this->actingAs($staff)->get('/admin/catalogues/courses');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/catalogues/course/new');

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.courses.new');
    }

    public function test_admin_can_create_course(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/catalogues/course/insert', [
                'name' => 'Curso Nuevo',
                'crew_id' => $this->crew->id,
            ]);

        $response->assertRedirect(route('admin.catalogues.courses.show'));
        $this->assertDatabaseHas('courses', [
            'name' => 'Curso Nuevo',
            'crew_id' => $this->crew->id,
            'is_active' => true,
        ]);
    }

    public function test_create_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/catalogues/course/insert', []);

        $response->assertSessionHasErrors(['name', 'crew_id']);
    }

    public function test_create_validates_crew_id_is_integer(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/catalogues/course/insert', [
                'name' => 'Curso Test',
                'crew_id' => 'invalid',
            ]);

        $response->assertSessionHasErrors('crew_id');
    }

    public function test_admin_can_view_edit_form(): void
    {
        $course = Course::create([
            'name' => 'Curso Existente',
            'crew_id' => $this->crew->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/catalogues/course/edit/' . $course->id);

        $response->assertStatus(200);
        $response->assertViewIs('admin.catalogues.courses.edit');
        $response->assertViewHas('course');
    }

    public function test_admin_can_update_course(): void
    {
        $course = Course::create([
            'name' => 'Curso Original',
            'crew_id' => $this->crew->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->put('/admin/catalogues/course/update/' . $course->id, [
                'name' => 'Curso Modificado',
                'crew_id' => $this->crew->id,
            ]);

        $response->assertRedirect(route('admin.catalogues.courses.show'));
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Curso Modificado',
        ]);
    }

    public function test_admin_can_delete_course(): void
    {
        $course = Course::create([
            'name' => 'Curso a Eliminar',
            'crew_id' => $this->crew->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete('/admin/catalogues/course/delete/' . $course->id);

        $response->assertRedirect(route('admin.catalogues.courses.show'));
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'is_active' => false,
        ]);
    }

    public function test_deleted_courses_are_not_shown_in_list(): void
    {
        $activeCourse = Course::create([
            'name' => 'Curso Activo',
            'crew_id' => $this->crew->id,
            'is_active' => true,
        ]);

        $inactiveCourse = Course::create([
            'name' => 'Curso Inactivo',
            'crew_id' => $this->crew->id,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/catalogues/courses');

        $response->assertSee('Curso Activo');
        $response->assertDontSee('Curso Inactivo');
    }

    public function test_guest_cannot_access_courses(): void
    {
        $response = $this->get('/admin/catalogues/courses');

        $response->assertRedirect(route('login'));
    }
}
