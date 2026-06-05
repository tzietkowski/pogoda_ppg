<?php

declare(strict_types=1);

namespace App\Services\Weather;

/**
 * Base weather provider implementation.
 *
 * Provides common provider metadata and a shared cache layer for raw API responses.
 */
abstract class AbstractWeatherProvider implements WeatherProviderInterface
{
    /**
     * Cached raw response from the external provider.
     *
     * @var array|string|null
     */
    protected array|string|null $cachedData = null;

    public function __construct(
        protected string $providerName
    ) {}

    /**
     * Get the provider display name.
     */
    public function getProviderName(): string
    {
        return $this->providerName;
    }

    /**
     * Return cached raw data or fetch it once from the external API.
     *
     * @return array|string
     */
    protected function getCachedData(): array|string
    {
        if ($this->cachedData === null) {
            $this->cachedData = $this->fetchRawData();
        }

        return $this->cachedData;
    }

    /**
     * Fetch raw weather data from the concrete provider.
     *
     * @return array|string
     */
    abstract protected function fetchRawData(): array|string;
}
