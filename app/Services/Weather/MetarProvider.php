<?php

declare(strict_types=1);

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http;
use Exception;
use App\Models\Spot;

/**
 * METAR weather provider.
 */
class MetarProvider extends AbstractWeatherProvider
{
    public function __construct()
    {
        parent::__construct('METAR');
    }

    /**
     * Get the current wind speed reported by the METAR station.
     */
    public function getWindSpeed(Spot $spot): float
    {
        $metarCode = $spot->metar_code;

        $cacheKey = "metar_{$metarCode}";
        $data = $this->getCachedData($cacheKey, ['metarCode' => $metarCode]);

        $windKnots = $data[0]['wspd'] ?? 0;

        return round($windKnots * 0.514444, 1);
    }

    /**
     * Get the current wind direction reported by the METAR station.
     */
    public function getWindDirection(Spot $spot): int
    {
        $metarCode = $spot->metar_code;

        $cacheKey = "metar_{$metarCode}";
        $data = $this->getCachedData($cacheKey, ['metarCode' => $metarCode]);

        return (int) ($data[0]['wdir'] ?? 0);
    }

    /**
     * Fetch raw METAR data for the requested station.
     *
     * @return array<int, mixed>
     * @throws Exception
     */
    protected function fetchRawData(array $params = []): array
    {
        $station = $params['metarCode'];

        $url = "https://aviationweather.gov/api/data/metar?ids={$station}&format=json";

        $response = Http::timeout(5)->get($url);

        if ($response->failed() || empty($response->json())) {
            throw new Exception("Awaria sieci: Nie udało się pobrać danych METAR dla stacji {$station}");
        }

        return $response->json();
    }
}
