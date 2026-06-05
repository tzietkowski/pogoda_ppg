<?php

declare(strict_types=1);

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http;
use Exception;

/**
 * Open-Meteo provider.
 *
 * Fetches the current weather for the configured coordinates and converts wind speed
 * from km/h to m/s for the application's analysis logic.
 */
class OpenMeteoProvider extends AbstractWeatherProvider
{
    public function __construct()
    {
        parent::__construct('Open-Meteo');
    }

    /**
     * Get the current wind speed from Open-Meteo.
     */
    public function getWindSpeed(): float
    {
        $data = $this->getCachedData();
        $windKmh = $data['current_weather']['windspeed'];

        return round($windKmh / 3.6, 1);
    }

    /**
     * Get the wind direction reported by Open-Meteo.
     */
    public function getWindDirection(): int
    {
        $data = $this->getCachedData();

        return (int) $data['current_weather']['winddirection'];
    }

    /**
     * Fetch raw weather data from the Open-Meteo API.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    protected function fetchRawData(): array
    {
        $lat = config('weather.latitude');
        $lng = config('weather.longitude');

        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lng}&current_weather=true";

        $response = Http::timeout(5)->get($url);

        if ($response->failed()) {
            throw new Exception("Awaria sieci: Nie udało się pobrać danych z Open-Meteo");
        }

        return $response->json();
    }
}
