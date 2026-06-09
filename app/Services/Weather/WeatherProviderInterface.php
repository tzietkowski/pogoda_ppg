<?php

declare(strict_types=1);

namespace App\Services\Weather;

use App\Models\Spot;

/**
 * Contract for weather data providers.
 *
 * Providers must expose a readable name, wind speed in meters per second,
 * and wind direction in degrees.
 */
interface WeatherProviderInterface
{
    /**
     * Get the provider display name.
     */
    public function getProviderName(): string;

    /**
     * Get the current wind speed in meters per second.
     */
    public function getWindSpeed(Spot $spot): float;

    /**
     * Get the current wind direction in degrees.
     */
    public function getWindDirection(Spot $spot): int;
}
