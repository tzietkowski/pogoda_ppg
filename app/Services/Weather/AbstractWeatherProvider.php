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
    protected array $cachedData = [];

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
    protected function getCachedData(string $cacheKey, array $params = []): array|string
    {
        if (!isset($this->cachedData[$cacheKey])) {
            $this->cachedData[$cacheKey] = $this->fetchRawData($params);
        }

        return $this->cachedData[$cacheKey];
    }

    /**
     * Fetch raw weather data from the concrete provider.
     *
     * @return array|string
     */
    abstract protected function fetchRawData(array $params = []): array|string;
}
