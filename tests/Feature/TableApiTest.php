<?php

use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['*'] // all abilities
    );
});

it('can list tables', function () {
    Table::factory()->count(3)->create();

    $this->getJson('/api/tables')
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ])
        ->assertJsonCount(3, 'data');
});

it('can show a single table', function () {
    $table = Table::factory()->create();

    $this->getJson("/api/tables/{$table->id}")
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity ?? null,
                'status' => $table->status,
                'chair_type' => $table->chair_type,
                'location' => $table->location,
                'min_guests' => $table->min_guests,
                'max_guests' => $table->max_guests,
                'is_smoking_allowed' => $table->is_smoking_allowed,
                'price_per_hour' => $table->price_per_hour,
                'description' => $table->description,
            ],
        ]);
});

it('can create a table', function () {
    $payload = [
        'table_number' => 'T123',
        'capacity' => 4,
        'seats' => 4,
        'status' => 'available',
        'chair_type' => 'standard',
        'location' => 'main_dining',
        'min_guests' => 1,
        'max_guests' => 3,
        'is_smoking_allowed' => false,
        'price_per_hour' => 50.00,
        'description' => 'Nice table',
    ];

    $this->postJson('/api/tables', $payload)
        ->assertCreated()
        ->assertJsonFragment([
            'table_number' => 'T123',
            'capacity' => 4,
            'status' => 'available',
            'chair_type' => 'standard',
            'location' => 'main_dining',
        ]);

    $this->assertDatabaseHas('tables', [
        'table_number' => 'T123',
    ]);
});

it('can update a table', function () {
    $table = Table::factory()->create([
        'table_number' => 'T215', // set explicitly for assertion to match
    ]);

    $payload = [
        'table_number' => 'T215', // keep same to pass unique rule
        'capacity' => 6,
        'seats' => 6,
        'status' => 'reserved',
        'chair_type' => 'booth',
        'location' => 'updated_location',
        'min_guests' => 2,
        'max_guests' => 3,
        'is_smoking_allowed' => true,
        'price_per_hour' => 75.50,
        'description' => 'Updated nice table',
    ];

    $this->putJson("/api/tables/{$table->id}", $payload)
        ->assertOk()
        ->assertJsonFragment([
            'table_number' => 'T215',
            'capacity' => 6,
            'seats' => 6,
            'status' => 'reserved',
            
            'location' => 'updated_location',
            'description' => 'Updated nice table',
            'is_smoking_allowed' => true,
            'chair_type' => 'booth',
            'min_guests' => 2,
            'max_guests' => 3,
            
            'price_per_hour' => 75.5,
        ]);
});


it('can delete a table', function () {
    $table = Table::factory()->create();

    $this->deleteJson("/api/tables/{$table->id}")
        ->assertOk()
        ->assertJson([
            'message' => 'Table deleted successfully',
        ]);

    $this->assertDatabaseMissing('tables', ['id' => $table->id]);
});

it('returns not found for missing table', function () {
    $this->getJson('/api/tables/999999')
        ->assertStatus(404);
});
