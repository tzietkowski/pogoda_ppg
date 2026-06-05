<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Weather\OpenMeteoProvider;
use App\Services\Weather\MetarProvider;
use Illuminate\Support\Facades\Http;

class WeatherProvidersTest extends TestCase
{
    /** @test */
    public function test_it_correctly_fetches_and_converts_open_meteo_data()
    {
        // 1. ARRANGE 
        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'current_weather' => [
                    'windspeed' => 18.0,
                    'winddirection' => 90
                ]
            ], 200)
        ]);

        // 2. ACT 
        $provider = new OpenMeteoProvider();
        $speed = $provider->getWindSpeed();
        $direction = $provider->getWindDirection();
        $name = $provider->getProviderName();

        // 3. ASSERT (Sprawdzenie)
        $this->assertEquals(5.0, $speed);
        $this->assertEquals(90, $direction);
        $this->assertEquals('Open-Meteo', $name);
    }

    /** @test */
    public function test_it_correctly_fetches_and_converts_metar_data()
    {
        // 1. ARRANGE 
        Http::fake([
            'aviationweather.gov/*' => Http::response([
                [
                    'wspd' => 10,
                    'wdir' => 180
                ]
            ], 200)
        ]);

        // 2. ACT
        $provider = new MetarProvider();
        $speed = $provider->getWindSpeed();
        $direction = $provider->getWindDirection();

        // 3. ASSERT 
        $this->assertEquals(5.1, $speed);
        $this->assertEquals(180, $direction);
    }

    /** @test */
    public function test_it_only_hits_the_api_once_due_to_caching()
    {
        // ARRANGE
        Http::fake([
            'api.open-meteo.com/*' => Http::response(['current_weather' => ['windspeed' => 10, 'winddirection' => 90]], 200)
        ]);

        $provider = new OpenMeteoProvider();

        // ACT
        $provider->getWindSpeed();
        $provider->getWindDirection();

        // ASSERT
        Http::assertSentCount(1);
    }
}
