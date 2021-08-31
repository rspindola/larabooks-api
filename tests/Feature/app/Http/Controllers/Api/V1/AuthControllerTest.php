<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    /**
     * LOGIN TESTS
     * Route: auth.login
     * Url: /auth/login
     */

    /**
     * Teste nao enviando credenciais.
     *
     * @return void
     */
    public function testUserShouldBeDeniedIfNotSendCredentials()
    {
        $request = $this->postJson(route('auth.login'));
        $request->assertStatus(422);
    }

    /**
     * Teste enviando usuario nao registrado
     *
     * @return void
     */
    public function testUserShouldBeDeniedIfNotRegistered()
    {
        $payload = [
            'email' => 'invalid@email.com',
            'password' => 'INVALID'
        ];

        $request = $this->postJson(route('auth.login'), $payload);
        $request->assertStatus(401);
        $request->assertJson(['errors' => ['main' => 'Wrong credentials']]);
    }

    /**
     * Teste enviando senha incorreta.
     *
     * @return void
     */
    public function testUserShouldSendWrongPassword()
    {
        $user = User::factory()->create();
        $payload = [
            'email' => $user->email,
            'password' => 'INVALID'
        ];

        $request = $this->postJson(route('auth.login'), $payload);
        $request->assertStatus(401);
        $request->assertJson(['errors' => ['main' => 'Wrong credentials']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testUserCanAuthenticate()
    {
        $user = User::factory()->create();
        $payload = [
            'email' => $user->email,
            'password' => 'secret123'
        ];

        $request = $this->postJson(route('auth.login'), $payload);
        $request->assertStatus(200);
        $request->assertJsonStructure(['access_token', 'expires_at']);
    }

    /**
     * REGISTER TESTS
     * Route: auth.register
     * Url: /auth/register
     */

    /**
     * Teste não enviando o nome.
     *
     * @return void
     */
    public function testUserShouldNotSendName()
    {
        $payload = [
            'name' => null,
            'email' => 'new@email.com',
            'password' => 'INVALID'
        ];

        $request = $this->postJson(route('auth.register'), $payload);
        $request->assertStatus(422);
        $request->assertJson(["message" => "The given data was invalid.", "errors" => ['name' => ["The name field is required."]]]);
    }

    /**
     * Teste não enviando o email.
     *
     * @return void
     */
    public function testUserShouldNotSendEmail()
    {
        $payload = [
            'name' => 'Renato',
            'email' => null,
            'password' => 'INVALID'
        ];

        $request = $this->postJson(route('auth.register'), $payload);
        $request->assertStatus(422);
        $request->assertJson(["message" => "The given data was invalid.", "errors" => ['email' => ["The email field is required."]]]);
    }

/**
     * Teste não enviando a senha.
     *
     * @return void
     */
    public function testUserShouldNotSendPassword()
    {
        $payload = [
            'name' => 'Renato',
            'email' => 'new@email.com',
            'password' => null
        ];

        $request = $this->postJson(route('auth.register'), $payload);
        $request->assertStatus(422);
        $request->assertJson(["message" => "The given data was invalid.", "errors" => ['password' => ["The password field is required."]]]);
    }

    /**
     * Teste enviando usuario já registrado
     *
     * @return void
     */
    public function testUserShouldBeDeniedIfAlreadRegistered()
    {
        $user = User::factory()->create();
        $payload = [
            'name' => 'Renato',
            'email' => $user->email,
            'password' => 'secret123'
        ];

        $request = $this->postJson(route('auth.register'), $payload);
        $request->assertStatus(401);
        $request->assertJson(['errors' => ['main' => 'User already register']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testUserCanRegistered()
    {
        $payload = [
            'name' => 'Renato',
            'email' => 'new@email.com',
            'password' => 'secret123'
        ];

        $request = $this->postJson(route('auth.register'), $payload);
        $request->assertStatus(200);
        $request->assertJsonStructure(['access_token', 'expires_at']);
    }

    /**
     * Teste nao enviando credenciais.
     *
     * @return void
     */
    public function testUseLogout()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $request = $this->getJson(route('auth.logout'));

        $request->assertStatus(200);
        $request->assertJson(['success' => ['main' => 'Logout successfully']]);
    }
}
