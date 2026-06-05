<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Weather\OpenMeteoProvider;
use App\Services\Weather\MetarProvider;

class WeatherAnalyzerService
{
    public function __construct(
        private OpenMeteoProvider $openMeteo,
        private MetarProvider $metar
    ) {}

    public function generateReport(): array
    {
        $meteoWind = $this->openMeteo->getWindSpeed();
        $meteoDir = $this->openMeteo->getWindDirection();

        $metarWind = $this->metar->getWindSpeed();
        $metarDir = $this->metar->getWindDirection();

        $averageWind = round(($meteoWind + $metarWind) / 2, 1);

        $isSafe = $averageWind <= config('weather.max_safe_wind');

        $windDifference = abs($meteoWind - $metarWind);
        if ($windDifference > 3.0) {
            $isSafe = false;
            $warning = "UWAGA: Niestabilne warunki! Duża rozbieżność między stacjami ($windDifference m/s).";
        } else {
            $warning = null;
        }

        return [
            'status' => $isSafe ? 'GO' : 'NO GO',
            'average_wind_ms' => $averageWind,
            'is_safe_to_fly' => $isSafe,
            'warning' => $warning,
            'details' => [
                'open_meteo' => [
                    'wind_speed_ms' => $meteoWind,
                    'direction_deg' => $meteoDir,
                ],
                'metar_eppo' => [
                    'wind_speed_ms' => $metarWind,
                    'direction_deg' => $metarDir,
                ]
            ]
        ];
    }
}
