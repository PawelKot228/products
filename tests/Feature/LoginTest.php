<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $password = 'password123';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => \Hash::make($this->password),
        ]);
    }

    public function test_incorrect_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'incorrectEmail@example.com',
            'password' => "{$this->password}_!@%asd",
        ]);

        $response->assertStatus(401);
    }

    public function test_successful_login(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => $this->password,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
            ],
            'token',
        ]);
        $response->assertJson([
            'user' => $this->user->toArray(),
        ]);
    }
}
