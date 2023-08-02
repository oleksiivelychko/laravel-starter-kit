<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginScreenCanBeRendered()
    {
        $response = $this->get('/'.app()->getLocale().'/login');
        $response->assertStatus(200);
    }

    public function testUsersCanAuthenticateUsingTheLoginScreen()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->post('/'.app()->getLocale().'/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME.app()->getLocale());
    }

    public function testUsersCanNotAuthenticateWithInvalidPassword()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->post('/'.app()->getLocale().'/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
