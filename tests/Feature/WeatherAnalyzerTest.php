<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test the flight conditions API and the generated weather report.
 */
class WeatherAnalyzerTest extends TestCase
{
    /**
     * Verify the API returns GO when average wind is below the safe threshold.
     */
    use RefreshDatabase;

    public function test_it_returns_go_status_when_wind_is_safe()
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response(['current_weather' => ['windspeed' => 10.8, 'winddirection' => 90]], 200),
            'aviationweather.gov/*' => Http::response([['wspd' => 6, 'wdir' => 90]], 200),
        ]);

        $response = $this->getJson('/api/conditions');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'GO')
            ->assertJsonPath('is_safe_to_fly', true)
            ->assertJsonPath('average_wind_ms', 3.1);
    }

    /**
     * Verify the API returns NO GO when wind exceeds the safe threshold.
     */
    public function test_it_returns_no_go_status_when_wind_is_too_strong()
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response(['current_weather' => ['windspeed' => 28.8, 'winddirection' => 180]], 200),
            'aviationweather.gov/*' => Http::response([['wspd' => 16, 'wdir' => 180]], 200),
        ]);

        $response = $this->getJson('/api/conditions');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'NO GO')
            ->assertJsonPath('is_safe_to_fly', false);
    }

    /**
     * Verify the API warns when provider results are inconsistent.
     */
    public function test_it_detects_unstable_weather_and_issues_a_warning()
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response(['current_weather' => ['windspeed' => 7.2, 'winddirection' => 200]], 200),
            'aviationweather.gov/*' => Http::response([['wspd' => 14, 'wdir' => 210]], 200),
        ]);

        $response = $this->getJson('/api/conditions');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'NO GO')
            ->assertJsonPath('is_safe_to_fly', false);

        $this->assertStringContainsString('UWAGA: Niestabilne warunki', $response->json('warning'));
    }
}
