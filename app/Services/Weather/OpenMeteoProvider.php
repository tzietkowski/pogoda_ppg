<?php

declare(strict_types=1);

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http;
use Exception;
use App\Models\Spot;

/**
 * Open-Meteo provider.
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
    public function getWindSpeed(Spot $spot): float
    {
        $lat = $spot->latitude;
        $lng = $spot->longitude;

        $cacheKey = "openmeteo_{$lat}_{$lng}";
        $data = $this->getCachedData($cacheKey, ['lat' => $lat, 'lng' => $lng]);

        return round($data['current_weather']['windspeed'] / 3.6, 1);
    }

    /**
     * Get the wind direction reported by Open-Meteo.
     */
    public function getWindDirection(Spot $spot): int
    {
        $lat = $spot->latitude;
        $lng = $spot->longitude;

        $cacheKey = "openmeteo_{$lat}_{$lng}";
        $data = $this->getCachedData($cacheKey, ['lat' => $lat, 'lng' => $lng]);

        return (int) $data['current_weather']['winddirection'];
    }

    /**
     * Fetch raw weather data from the Open-Meteo API.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    protected function fetchRawData(array $params = []): array
    {
        $lat = $params['lat'];
        $lng = $params['lng'];

        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lng}&current_weather=true";

        $response = Http::timeout(5)->get($url);

        if ($response->failed()) {
            throw new Exception("Awaria sieci: Nie udało się pobrać danych z Open-Meteo");
        }

        return $response->json();
    }
}
