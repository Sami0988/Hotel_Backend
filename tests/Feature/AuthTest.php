<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;


use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class); // For Pest
// OR for PHPUnit: add `use RefreshDatabase;` in your TestCase class


beforeEach(function () {
    // Create a user for testing
    $this->password = 'password123';
    $this->user = User::factory()->create([
        'email' => 'testlogin@example.com',
        'password' => Hash::make($this->password),
        'name' => 'Test User',
        'role' => 'Admin',
    ]);
});

it('can login and get a token with user data', function () {
    $response = postJson('/api/login', [
        'email' => $this->user->email,
        'password' => $this->password,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => [
                'name',
                'email',
                'role',
            ],
        ]);
});

it('cannot login with invalid credentials', function () {
    $response = postJson('/api/login', [
        'email' => $this->user->email,
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Invalid credentials']);
});

it('can logout with valid token', function () {
    Sanctum::actingAs($this->user);

    $response = postJson('/api/logout'); // Instead of deleteJson


    $response->assertStatus(200)
        ->assertJson(['message' => 'Logged out']);
});

it('cannot logout without token', function () {
$response = postJson('/api/logout'); // Instead of deleteJson

    $response->assertStatus(401); // Unauthorized
});
