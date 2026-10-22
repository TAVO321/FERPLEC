<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_is_reachable(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@ferplec.bo',
            'password' => Hash::make('secreto123'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@ferplec.bo',
            'password' => 'secreto123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create(['email' => 'admin@ferplec.bo']);

        $this->post('/admin/login', [
            'email' => 'admin@ferplec.bo',
            'password' => 'incorrecta',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_area_requires_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_non_admin_user_cannot_login(): void
    {
        User::factory()->create([
            'email' => 'usuario@ferplec.bo',
            'password' => Hash::make('secreto123'),
        ]);

        $this->post('/admin/login', [
            'email' => 'usuario@ferplec.bo',
            'password' => 'secreto123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_non_admin_user_is_forbidden(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_user_can_logout(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/logout')
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
