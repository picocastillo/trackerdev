<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    private int $seniorRoleId;

    private int $juniorRoleId;

    private int $clientRoleId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seniorRoleId = $this->insertRole('senior', 2);
        $this->juniorRoleId = $this->insertRole('junior', 0.8);
        $this->clientRoleId = $this->insertRole('stackeholder', 1);
    }

    public function test_guest_is_redirected_from_users_index(): void
    {
        $this->get('/users')->assertRedirect('/login');
    }

    public function test_non_senior_cannot_access_users_index(): void
    {
        $junior = $this->makeUser([
            'email' => 'junior@example.com',
            'role_id' => $this->juniorRoleId,
        ]);

        $this->actingAs($junior)
            ->get('/users')
            ->assertUnauthorized();
    }

    public function test_senior_can_list_users(): void
    {
        $senior = $this->makeSenior();
        $this->makeUser([
            'name' => 'Ana Junior',
            'email' => 'ana@example.com',
            'role_id' => $this->juniorRoleId,
        ]);

        $this->actingAs($senior)
            ->get('/users')
            ->assertOk()
            ->assertSee('Usuarios')
            ->assertSee($senior->email)
            ->assertSee('Ana Junior')
            ->assertSee('Nuevo usuario');
    }

    public function test_senior_can_view_create_form(): void
    {
        $this->actingAs($this->makeSenior())
            ->get('/users/create')
            ->assertOk()
            ->assertSee('Nuevo usuario')
            ->assertSee('name="role_id"', false);
    }

    public function test_senior_can_create_a_user(): void
    {
        $senior = $this->makeSenior();

        $response = $this->actingAs($senior)->post('/users/create', [
            'name' => 'Nuevo Dev',
            'email' => 'nuevo@example.com',
            'password' => 'secret123',
            'role_id' => $this->juniorRoleId,
            'is_active' => '1',
            'by_hours' => '0',
        ]);

        $response->assertRedirect('/users');
        $response->assertSessionHas('alert-success', 'Usuario creado con éxito');

        $created = User::where('email', 'nuevo@example.com')->first();

        $this->assertNotNull($created);
        $this->assertSame('Nuevo Dev', $created->name);
        $this->assertSame($this->juniorRoleId, (int) $created->role_id);
        $this->assertTrue($created->is_active);
        $this->assertFalse($created->by_hours);
        $this->assertTrue(Hash::check('secret123', $created->password));
    }

    public function test_create_requires_name_email_password_and_role(): void
    {
        $this->actingAs($this->makeSenior())
            ->from('/users/create')
            ->post('/users/create', [])
            ->assertRedirect('/users/create')
            ->assertSessionHasErrors(['name', 'email', 'password', 'role_id']);
    }

    public function test_create_rejects_duplicate_email(): void
    {
        $senior = $this->makeSenior();

        $this->actingAs($senior)
            ->from('/users/create')
            ->post('/users/create', [
                'name' => 'Copia',
                'email' => $senior->email,
                'password' => 'secret123',
                'role_id' => $this->juniorRoleId,
            ])
            ->assertRedirect('/users/create')
            ->assertSessionHasErrors('email');
    }

    public function test_create_rejects_short_password(): void
    {
        $this->actingAs($this->makeSenior())
            ->from('/users/create')
            ->post('/users/create', [
                'name' => 'Corto',
                'email' => 'corto@example.com',
                'password' => '1234567',
                'role_id' => $this->juniorRoleId,
            ])
            ->assertRedirect('/users/create')
            ->assertSessionHasErrors('password');
    }

    public function test_senior_can_view_edit_form(): void
    {
        $senior = $this->makeSenior();
        $target = $this->makeUser([
            'name' => 'Editable',
            'email' => 'edit@example.com',
        ]);

        $this->actingAs($senior)
            ->get("/users/{$target->id}/edit")
            ->assertOk()
            ->assertSee('Editar usuario')
            ->assertSee('Editable')
            ->assertSee('edit@example.com');
    }

    public function test_senior_can_update_a_user_without_changing_password(): void
    {
        $senior = $this->makeSenior();
        $target = $this->makeUser([
            'name' => 'Antes',
            'email' => 'antes@example.com',
            'password' => 'original-password',
            'role_id' => $this->juniorRoleId,
            'is_active' => true,
            'by_hours' => false,
        ]);
        $originalHash = $target->password;

        $response = $this->actingAs($senior)->put("/users/{$target->id}/edit", [
            'name' => 'Después',
            'email' => 'despues@example.com',
            'password' => '',
            'role_id' => $this->clientRoleId,
            'is_active' => '1',
            'by_hours' => '1',
        ]);

        $response->assertRedirect('/users');
        $response->assertSessionHas('alert-success', 'Usuario actualizado con éxito');

        $target->refresh();

        $this->assertSame('Después', $target->name);
        $this->assertSame('despues@example.com', $target->email);
        $this->assertSame($this->clientRoleId, (int) $target->role_id);
        $this->assertTrue($target->is_active);
        $this->assertTrue($target->by_hours);
        $this->assertSame($originalHash, $target->password);
    }

    public function test_senior_can_update_password_when_provided(): void
    {
        $senior = $this->makeSenior();
        $target = $this->makeUser([
            'email' => 'pwd@example.com',
            'password' => 'old-password',
        ]);

        $this->actingAs($senior)->put("/users/{$target->id}/edit", [
            'name' => $target->name,
            'email' => $target->email,
            'password' => 'new-password',
            'role_id' => $target->role_id,
            'is_active' => '1',
        ]);

        $this->assertTrue(Hash::check('new-password', $target->fresh()->password));
    }

    public function test_cannot_deactivate_own_user_from_edit_form(): void
    {
        $senior = $this->makeSenior();

        $this->actingAs($senior)
            ->from("/users/{$senior->id}/edit")
            ->put("/users/{$senior->id}/edit", [
                'name' => $senior->name,
                'email' => $senior->email,
                'role_id' => $senior->role_id,
                'is_active' => '0',
            ])
            ->assertRedirect("/users/{$senior->id}/edit")
            ->assertSessionHas('alert-danger', 'No podés desactivar tu propio usuario.');

        $this->assertTrue($senior->fresh()->is_active);
    }

    public function test_cannot_deactivate_own_user_from_list(): void
    {
        $senior = $this->makeSenior();

        $this->actingAs($senior)
            ->from('/users')
            ->patch("/users/{$senior->id}/deactivate")
            ->assertRedirect('/users')
            ->assertSessionHas('alert-danger', 'No podés desactivar tu propio usuario.');

        $this->assertTrue($senior->fresh()->is_active);
    }

    public function test_senior_can_deactivate_another_user(): void
    {
        $senior = $this->makeSenior();
        $target = $this->makeUser([
            'email' => 'off@example.com',
            'is_active' => true,
        ]);

        $this->actingAs($senior)
            ->patch("/users/{$target->id}/deactivate")
            ->assertRedirect('/users')
            ->assertSessionHas('alert-success', 'Usuario desactivado con éxito');

        $this->assertFalse($target->fresh()->is_active);
    }

    public function test_senior_can_activate_another_user(): void
    {
        $senior = $this->makeSenior();
        $target = $this->makeUser([
            'email' => 'on@example.com',
            'is_active' => false,
        ]);

        $this->actingAs($senior)
            ->patch("/users/{$target->id}/activate")
            ->assertRedirect('/users')
            ->assertSessionHas('alert-success', 'Usuario reactivado con éxito');

        $this->assertTrue($target->fresh()->is_active);
    }

    public function test_deactivated_user_cannot_login(): void
    {
        $this->makeUser([
            'email' => 'locked@example.com',
            'password' => 'secret123',
            'is_active' => false,
        ]);

        $this->from('/login')
            ->post('/login', [
                'email' => 'locked@example.com',
                'password' => 'secret123',
            ])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_edit_missing_user_returns_not_found(): void
    {
        $this->actingAs($this->makeSenior())
            ->get('/users/999/edit')
            ->assertNotFound();
    }

    public function test_index_shows_role_and_status(): void
    {
        $senior = $this->makeSenior();
        $this->makeUser([
            'name' => 'Inactivo',
            'email' => 'inactivo@example.com',
            'is_active' => false,
        ]);

        $this->actingAs($senior)
            ->get('/users')
            ->assertOk()
            ->assertSee('senior')
            ->assertSee('Activo')
            ->assertSee('Desactivado')
            ->assertSee('Inactivo');
    }

    private function makeSenior(): User
    {
        return $this->makeUser([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'role_id' => $this->seniorRoleId,
            'is_active' => true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'name' => 'Usuario',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password123',
            'role_id' => $this->juniorRoleId,
            'is_active' => true,
            'by_hours' => false,
        ], $overrides));
    }

    private function insertRole(string $seniority, float $weight): int
    {
        return (int) DB::table('roles')->insertGetId([
            'seniority' => $seniority,
            'weight' => $weight,
        ]);
    }
}
