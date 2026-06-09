<?php

namespace Tests\Feature;

use App\Models\Spot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherAnalyzerTest extends TestCase
{
    use RefreshDatabase;

    protected Spot $spot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->spot = Spot::create([
            'name' => 'Czerwonak',
            'latitude' => 52.47,
            'longitude' => 16.98,
            'metar_code' => 'EPPO',
        ]);
    }

    public function test_it_returns_go_status_when_wind_is_safe()
    {

        Http::fake([
            'api.open-meteo.com/*' => Http::response(['current_weather' => ['windspeed' => 7.2, 'winddirection' => 180]]), // 7.2 km/h = 2 m/s
            'aviationweather.gov/*' => Http::response([['wspd' => 6, 'wdir' => 190]]), // 6 knots ≈ 3.1 m/s
        ]);

        $response = $this->getJson('/api/conditions');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'GO')
            ->assertJsonPath('is_safe_to_fly', true)
            ->assertJsonPath('average_wind_ms', 2.6); // Zaktualizowana średnia: (2 + 3.1) / 2 = 2.55, po zaokrągleniu 2.6
    }

    public function test_it_returns_no_go_status_when_wind_is_too_strong()
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response(['current_weather' => ['windspeed' => 54.0, 'winddirection' => 180]]), // 54 km/h = 15 m/s
            'aviationweather.gov/*' => Http::response([['wspd' => 29, 'wdir' => 190]]), // 29 knots ≈ 14.9 m/s
        ]);

        $response = $this->getJson('/api/conditions');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'NO GO')
            ->assertJsonPath('is_safe_to_fly', false);
    }

    public function test_it_detects_unstable_weather_and_issues_a_warning()
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response(['current_weather' => ['windspeed' => 7.2, 'winddirection' => 180]]), // 2 m/s
            'aviationweather.gov/*' => Http::response([['wspd' => 16, 'wdir' => 190]]), // 16 knots ≈ 8.2 m/s
        ]);

        $response = $this->getJson('/api/conditions');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'NO GO')
            ->assertJsonPath('is_safe_to_fly', false);

        $this->assertStringContainsString('UWAGA: Niestabilne warunki', $response->json('warning'));
    }
}
