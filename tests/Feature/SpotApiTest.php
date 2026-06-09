<?php

namespace Tests\Feature;

use App\Models\Spot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpotApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_fetch_a_list_of_spots()
    {
        Spot::create([
            'name' => 'Testowe Startowisko',
            'latitude' => 52.47000,
            'longitude' => 16.98000,
            'metar_code' => 'EPPO',
        ]);

        $response = $this->getJson('/api/spots');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonPath('0.name', 'Testowe Startowisko')
            ->assertJsonPath('0.metar_code', 'EPPO');
    }

    public function test_it_can_create_a_valid_spot_and_uppercase_metar()
    {
        $payload = [
            'name' => 'Gdańsk Rębiechowo',
            'latitude' => 54.377,
            'longitude' => 18.466,
            'metar_code' => 'epgd',
        ];

        $response = $this->postJson('/api/spots', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('metar_code', 'EPGD');

        $this->assertDatabaseHas('spots', [
            'name' => 'Gdańsk Rębiechowo',
            'metar_code' => 'EPGD'
        ]);
    }

    public function test_it_rejects_invalid_metar_code()
    {
        $payload = [
            'name' => 'Nieznane Lotnisko',
            'latitude' => 50.000,
            'longitude' => 15.000,
            'metar_code' => 'ABCD',
        ];

        $response = $this->postJson('/api/spots', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['metar_code']);
    }
}
