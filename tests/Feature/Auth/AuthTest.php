<?php

namespace Tests\Feature\Auth;

use App\DataObject\Test\AuthData;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    public function testRegister()
    {
        $response = $this->json('POST', '/api/auth/register', [
            'name' => AuthData::NAME,
            'email' => AuthData::EMAIL,
            'password' => AuthData::PASSWORD,
            'password_confirmation' => AuthData::PASSWORD
        ]);

        $response->assertStatus(200);
    }

    public function testRegisterWithDuplicateEmail()
    {
        User::factory()->withEmail(AuthData::EMAIL)->create();

        $response = $this->json('POST', '/api/auth/register', [
            'name' => AuthData::NAME,
            'email' => AuthData::EMAIL,
            'password' => AuthData::PASSWORD,
            'password_confirmation' => AuthData::PASSWORD
        ]);

        $response->assertStatus(422);
    }

    public function testVerifyEmail()
    {
        $user = User::factory()->withEmail(AuthData::EMAIL)->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->json('GET', $verificationUrl);

        $response->assertStatus(200);
    }

    public function testVerifyEmailInvalidToken()
    {
        $user = User::factory()->withEmail(AuthData::EMAIL)->unverified()->create();

        $response = $this->json('GET', '/api/auth/email/verify/'.$user->id.'/123');

        $response->assertStatus(403);
    }

    public function testLogin()
    {
        $verifiedUser = User::factory()->create();

        $response = $this->json('POST', '/api/auth/login', [
            'email' => $verifiedUser->email,
            'password' => AuthData::PASSWORD,
        ]);

        $response->assertStatus(200);
    }

    public function testLoginwithInvalidCredentials()
    {
        User::factory()->create();

        $response = $this->json('POST', '/api/auth/login', [
            'email' => AuthData::EMAIL,
            'password' => AuthData::PASSWORD,
        ]);

        $response->assertStatus(401);
    }


    public function testForgotPassword()
    {
        $verifiedUser = User::factory()->create();

        $response = $this->json('POST', '/api/auth/forgot-password', [
            'email' => $verifiedUser->email,
        ]);

        $response->assertStatus(200);
    }

    public function testForgotPasswordWithInvalidEmail()
    {
        User::factory()->create();

        $response = $this->json('POST', '/api/auth/forgot-password', [
            'email' => AuthData::EMAIL,
        ]);

        $response->assertStatus(422);
    }

    public function testResetPassword()
    {
        $verifiedUser = User::factory()->create();
        $token = Password::createToken($verifiedUser);

        $response = $this->json('POST', '/api/auth/reset-password', [
            'email' => $verifiedUser->email,
            'token' => $token,
            'password' => "123456Aa!",
            'password_confirmation' => "123456Aa!"
        ]);

        $response->assertStatus(200);
    }

    public function testResetPasswordwithInvalidToken()
    {
        $verifiedUser = User::factory()->create();
        $token = Str::random(10);

        $response = $this->json('POST', '/api/auth/reset-password', [
            'email' => $verifiedUser->email,
            'token' => $token,
            'password' => "123456Aa!",
            'password_confirmation' => "123456Aa!"
        ]);

        $response->assertStatus(401);
    }

    public function testLogout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('MyApp')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('POST', '/api/user/logout');

        $response->assertStatus(200);
    }

    public function testLogoutUnverifiedUser()
    {
        $unverifiedUser = User::factory()->unverified()->create();
        $this->actingAs($unverifiedUser);

        $response = $this->json('POST', '/api/user/logout');

        $response->assertStatus(403);
    }
}