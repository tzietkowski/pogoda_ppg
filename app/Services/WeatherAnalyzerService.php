<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Weather\OpenMeteoProvider;
use App\Services\Weather\MetarProvider;
use App\Models\WeatherConditionLog;
use App\Models\Spot;

/**
 * Analyze weather conditions from multiple providers and build a flight report.
 *
 * This service resolves provider data, applies business rules for safe flying,
 * records the report, and returns a unified response payload.
 */
class WeatherAnalyzerService
{
    public function __construct(
        private OpenMeteoProvider $openMeteo,
        private MetarProvider $metar

    ) {}

    /**
     * Generate a flight condition report and persist it to the weather log.
     *
     * @return array<string, mixed>
     */
    public function generateReport(Spot $spot): array
    {
        $meteoWind = $this->openMeteo->getWindSpeed($spot);
        $meteoDir = $this->openMeteo->getWindDirection($spot);

        $metarWind = $this->metar->getWindSpeed($spot);
        $metarDir = $this->metar->getWindDirection($spot);

        $averageWind = round(($meteoWind + $metarWind) / 2, 1);
        $isSafe = $averageWind <= config('weather.max_safe_wind');

        $windDifference = abs($meteoWind - $metarWind);
        if ($windDifference > 3.0) {
            $isSafe = false;
            $warning = "UWAGA: Niestabilne warunki! Duża rozbieżność między stacjami ($windDifference m/s).";
        } else {
            $warning = null;
        }

        $report = [
            'spot_name' => $spot->name,
            'status' => $isSafe ? 'GO' : 'NO GO',
            'average_wind_ms' => $averageWind,
            'is_safe_to_fly' => $isSafe,
            'warning' => $warning,
            'details' => [
                'open_meteo' => [
                    'wind_speed_ms' => $meteoWind,
                    'direction_deg' => $meteoDir,
                ],
                'metar_' . strtolower($spot->metar_code) => [
                    'wind_speed_ms' => $metarWind,
                    'direction_deg' => $metarDir,
                ],
            ],
        ];

        WeatherConditionLog::create($report);

        return $report;
    }
}
