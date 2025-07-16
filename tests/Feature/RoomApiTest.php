<?php

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // You can set up any necessary initialization here
});

it('can list rooms', function () {
    Room::factory()->count(3)->create();

    $response = $this->getJson('/api/rooms');

    $response->assertOk()
        ->assertJsonCount(3, 'data'); // paginate returns data key
});

it('can create a room', function () {
    $roomData = Room::factory()->make()->toArray();

    $response = $this->postJson('/api/rooms', $roomData);

    $response->assertCreated()
        ->assertJsonFragment(['room_number' => $roomData['room_number']]);
});

it('can show a room', function () {
    $room = Room::factory()->create();

    $response = $this->getJson("/api/rooms/{$room->id}");

    $response->assertOk()
        ->assertJsonFragment(['id' => $room->id]);
});

it('can update a room with PUT', function () {
    $room = Room::factory()->create();

    $updateData = [
        'room_number' => '999',
        'type' => 'Suite',
        'capacity' => 2,
        'price_per_night' => 250.00,
        'status' => 'available',
    ];

    $response = $this->putJson("/api/rooms/{$room->id}", $updateData);

    $response->assertOk()
        ->assertJsonFragment(['room_number' => '999']);
});

it('can partially update a room with PATCH', function () {
    $room = Room::factory()->create();

    $patchData = [
        'status' => 'maintenance',
    ];

    $response = $this->patchJson("/api/rooms/{$room->id}", $patchData);

    $response->assertOk()
        ->assertJsonFragment(['status' => 'maintenance']);
});

it('can delete a room', function () {
    $room = Room::factory()->create();

    $response = $this->deleteJson("/api/rooms/{$room->id}");

    $response->assertNoContent();

    $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
});
