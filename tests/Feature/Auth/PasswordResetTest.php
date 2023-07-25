<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function testResetPasswordLinkScreenCanBeRendered()
    {
        $response = $this->get('/'.app()->getLocale().'/forgot-password');
        $response->assertStatus(200);
    }

    /**
     * @throws \Exception
     */
    public function testResetPasswordLinkCanBeRequested()
    {
        Notification::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $this->post('/'.app()->getLocale().'/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * @throws \Exception
     */
    public function testResetPasswordScreenCanBeRendered()
    {
        Notification::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $this->post('/'.app()->getLocale().'/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/'.app()->getLocale().'/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    /**
     * @throws \Exception
     */
    public function testPasswordCanBeResetWithValidToken()
    {
        Notification::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $this->post('/'.app()->getLocale().'/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/'.app()->getLocale().'/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
