<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class JwtTest extends TestCase
{
    use RefreshDatabase;

    public const PASSWORD = 'password';

    public function testJwtRegisterUser()
    {
        /** @var User $user */
        $user = User::factory()->make();

        $response = $this->json('POST', '/jwt/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response
            ->assertJsonStructure(['message', 'user'])
            ->assertJson([
                'message' => 'User has been successfully registered.',
                'user' => true,
            ])
        ;
    }

    public function testJwtLoginUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->json('POST', '/jwt/login', [
            'email' => $user->email,
            'password' => self::PASSWORD,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'accessToken', 'tokenType', 'expiresIn',
        ]);
    }

    public function testJwtAuthenticatedUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->json('POST', '/jwt/login', [
            'email' => $user->email,
            'password' => self::PASSWORD,
        ]);

        $accessToken = json_decode($response->baseResponse->content())->accessToken;
        self::assertNotEmpty($accessToken, 'Empty access token');

        $response = $this->json(
            'GET',
            '/jwt/me',
            [],
            ['Authorization' => 'Bearer '.$accessToken]
        );

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testJwtRefreshToken()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->json('POST', '/jwt/login', [
            'email' => $user->email,
            'password' => self::PASSWORD,
        ]);

        $accessToken = json_decode($response->baseResponse->content())->accessToken;
        self::assertNotEmpty($accessToken, 'Empty access token');

        $response = $this->json(
            'GET',
            '/jwt/refresh',
            [],
            ['Authorization' => 'Bearer '.$accessToken]
        );

        $newAccessToken = json_decode($response->baseResponse->content())->accessToken;

        self::assertNotEmpty($newAccessToken, 'Empty refreshed access token');
        self::assertNotEquals($newAccessToken, $accessToken, 'Token did not refresh');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'accessToken', 'tokenType', 'expiresIn',
        ]);
    }

    public function testJwtLogoutUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->json('POST', '/jwt/login', [
            'email' => $user->email,
            'password' => self::PASSWORD,
        ]);

        $accessToken = json_decode($response->baseResponse->content())->accessToken;
        self::assertNotEmpty($accessToken, 'Empty access token');

        $response = $this->json(
            'POST',
            '/jwt/logout',
            [],
            ['Authorization' => 'Bearer '.$accessToken]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'Successfully logged out.']);
    }
}
