<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Weather\OpenMeteoProvider;
use App\Services\Weather\MetarProvider;
use Illuminate\Support\Facades\Http;
use App\Models\Spot;
use Illuminate\Foundation\Testing\RefreshDatabase;


/**
 * Test the weather provider implementations used by the analyzer.
 */
class WeatherProvidersTest extends TestCase
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
    /**
     * Verify Open-Meteo responses are parsed and converted correctly.
     */
    public function test_it_correctly_fetches_and_converts_open_meteo_data()
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'current_weather' => [
                    'windspeed' => 18.0,
                    'winddirection' => 90,
                ],
            ], 200),
        ]);

        $provider = new OpenMeteoProvider();
        $speed = $provider->getWindSpeed($this->spot);
        $direction = $provider->getWindDirection($this->spot);
        $name = $provider->getProviderName();

        $this->assertEquals(5.0, $speed);
        $this->assertEquals(90, $direction);
        $this->assertEquals('Open-Meteo', $name);
    }

    /**
     * Verify METAR responses are parsed and converted correctly.
     */
    public function test_it_correctly_fetches_and_converts_metar_data()
    {
        Http::fake([
            'aviationweather.gov/*' => Http::response([
                [
                    'wspd' => 10,
                    'wdir' => 180,
                ],
            ], 200),
        ]);

        $provider = new MetarProvider();
        $speed = $provider->getWindSpeed($this->spot);
        $direction = $provider->getWindDirection($this->spot);

        $this->assertEquals(5.1, $speed);
        $this->assertEquals(180, $direction);
    }

    /**
     * Ensure the provider caches the raw API response and performs a single request.
     */
    public function test_it_only_hits_the_api_once_due_to_caching()
    {
        Http::fake([
            'api.open-meteo.com/*' => Http::response(['current_weather' => ['windspeed' => 10, 'winddirection' => 90]], 200),
        ]);

        $provider = new OpenMeteoProvider();
        $provider->getWindSpeed($this->spot);
        $provider->getWindDirection($this->spot);

        Http::assertSentCount(1);
    }
}
