<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function testConfirmPasswordScreenCanBeRendered()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/'.app()->getLocale().'/confirm-password');

        $response->assertStatus(200);
    }

    public function testPasswordCanBeConfirmed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/'.app()->getLocale().'/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function testPasswordIsNotConfirmedWithInvalidPassword()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/'.app()->getLocale().'/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
