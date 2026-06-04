<?php

declare(strict_types=1);

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http; // Wbudowany klient HTTP Laravela
use Exception;
use Override;

class OpenMeteoProvider extends AbstractWeatherProvider
{

    public function getProviderName(): string
    {
        return $this->providerName;
    }

    #[Override]
    public function getWindSpeed(): float
    {
        $data = $this->getCachedData();
        $windKmh = $data['current_weather']['windspeed'];

        return round($windKmh / 3.6, 1);
    }

    #[Override]
    public function getWindDirection(): int
    {
        $data = $this->getCachedData();
        return (int) $data['current_weather']['winddirection'];
    }

    #[Override]
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
