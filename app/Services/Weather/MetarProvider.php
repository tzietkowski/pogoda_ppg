<?php

declare(strict_types=1);

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http;
use Exception;

class MetarProvider extends AbstractWeatherProvider
{
    public function __construct()
    {
        parent::__construct('METAR (Poznań Ławica)');
    }

    public function getWindSpeed(): float
    {
        $data = $this->getCachedData();

        $windKnots = $data[0]['wspd'] ?? 0;

        return round($windKnots * 0.514444, 1);
    }

    public function getWindDirection(): int
    {
        $data = $this->getCachedData();

        return (int) ($data[0]['wdir'] ?? 0);
    }

    protected function fetchRawData(): array
    {
        $station = config('weather.metar_station');

        $url = "https://aviationweather.gov/api/data/metar?ids={$station}&format=json";

        $response = Http::timeout(5)->get($url);

        if ($response->failed() || empty($response->json())) {
            throw new Exception("Awaria sieci: Nie udało się pobrać danych METAR dla stacji {$station}");
        }

        return $response->json();
    }
}
