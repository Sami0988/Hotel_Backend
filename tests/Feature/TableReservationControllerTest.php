<?php



use App\Models\Table;
use App\Models\TableReservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Cre
    $this->table = Table::factory()->create([
        'capacity' => 4,
        'status' => 'available',
    ]);
});

it('lists all reservations', function () {
    TableReservation::factory()->count(3)->create([
        'table_id' => $this->table->id,
        'reservation_time' => now()->addDay(),
    ]);

    $response = $this->getJson('/api/table-reservations');

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

it('creates a new reservation', function () {
    $payload = [
        'name' => 'John Doe',
        'phone' => '0912345678',
        'table_id' => $this->table->id,
        'reservation_time' => now()->addDay()->format('Y-m-d H:i:s'),
        'guests_count' => 2,
        'special_requests' => 'Birthday setup',
    ];

    $response = $this->postJson('/api/table-reservations', $payload);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'name' => 'John Doe',
            'phone' => '0912345678',
            'table_id' => $this->table->id,
            'guests_count' => 2,
            'special_requests' => 'Birthday setup',
            'status' => 'pending',
        ]);

    $this->assertDatabaseHas('table_reservations', [
        'name' => 'John Doe',
        'phone' => '0912345678',
        'table_id' => $this->table->id,
    ]);
});

it('shows a reservation', function () {
    $reservation = TableReservation::factory()->create([
        'table_id' => $this->table->id,
        'reservation_time' => now()->addDay(),
    ]);

    $response = $this->getJson("/api/table-reservations/{$reservation->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $reservation->id]);
});

it('updates a reservation', function () {
    $reservation = TableReservation::factory()->create([
        'table_id' => $this->table->id,
        'reservation_time' => now()->addDay(),
        'guests_count' => 2,
    ]);

    $payload = [
        'guests_count' => 3,
        'status' => 'confirmed',
    ];

    $response = $this->putJson("/api/table-reservations/{$reservation->id}", $payload);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'guests_count' => 3,
            'status' => 'confirmed',
        ]);
});

it('deletes a reservation', function () {
    $reservation = TableReservation::factory()->create([
        'table_id' => $this->table->id,
    ]);

    $response = $this->deleteJson("/api/table-reservations/{$reservation->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Reservation deleted']);

    $this->assertDatabaseMissing('table_reservations', ['id' => $reservation->id]);
});

it('returns available tables', function () {
    $payload = [
        'reservation_time' => now()->addDay()->format('Y-m-d H:i:s'),
        'guests_count' => 2,
    ];

    $response = $this->getJson('/api/tablesReservation/available?' . http_build_query($payload));

    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $this->table->id]);
});
