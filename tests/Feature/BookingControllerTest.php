<?php

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create users
    $this->adminUser = User::factory()->create(['role' => 'admin']);
    $this->employeeUser = User::factory()->create(['role' => 'employee']);
    $this->guestUser = User::factory()->create(['role' => 'guest']); // unauthorized

    // Create a room for booking
    $this->room = Room::factory()->create();
});

it('blocks unauthenticated users', function () {
    $response = $this->getJson('/api/bookings');
    $response->assertUnauthorized();
});

it('blocks users without the required role', function () {
    Sanctum::actingAs($this->guestUser);

    $response = $this->getJson('/api/bookings');
    $response->assertForbidden();
});

it('allows admin to list bookings', function () {
    Sanctum::actingAs($this->adminUser);

    Booking::factory()->count(3)->create(['room_id' => $this->room->id, 'user_id' => $this->adminUser->id]);

    $response = $this->getJson('/api/bookings');

    $response->assertOk()
        ->assertJsonCount(3)
        ->assertJsonStructure([
            '*' => ['id', 'user_id', 'room_id', 'check_in', 'check_out', 'guests_count', 'total_price', 'status', 'special_requests', 'created_at', 'updated_at']
        ]);
});

it('allows employee to create a booking', function () {
    Sanctum::actingAs($this->employeeUser);

    $payload = [
        'user_id' => $this->employeeUser->id,
        'room_id' => $this->room->id,
        'check_in' => now()->addDay()->toDateString(),
        'check_out' => now()->addDays(2)->toDateString(),
        'guests_count' => 2,
        'total_price' => 100,
        'status' => 'pending',
        'special_requests' => 'None',
    ];

    $response = $this->postJson('/api/bookings', $payload);

    $response->assertCreated()
        ->assertJsonFragment(['status' => 'pending']);
});

it('allows admin to update a booking', function () {
    Sanctum::actingAs($this->adminUser);

    $booking = Booking::factory()->create([
        'room_id' => $this->room->id,
        'user_id' => $this->adminUser->id,
        'status' => 'pending',
    ]);

    $payload = [
        'status' => 'confirmed',
    ];

    $response = $this->putJson("/api/bookings/{$booking->id}", $payload);

    $response->assertOk()
        ->assertJsonFragment(['status' => 'confirmed']);
});

it('allows admin to delete a booking', function () {
    Sanctum::actingAs($this->adminUser);

    $booking = Booking::factory()->create([
        'room_id' => $this->room->id,
        'user_id' => $this->adminUser->id,
    ]);

    $response = $this->deleteJson("/api/bookings/{$booking->id}");

    $response->assertOk()
        ->assertJson(['message' => 'Booking deleted successfully']);

    $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
});
