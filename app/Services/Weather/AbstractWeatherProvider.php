<?php

declare(strict_types=1);

namespace App\Services\Weather;

abstract class AbstractWeatherProvider implements WeatherProviderInterface
{

    public function __construct(
        protected string $providerName
    ) {}

    public function getProviderName(): string
    {
        return $this->providerName;
    }

    // nie wiemy jakie dane beda zwracane
    protected array|string|null $cachedData = null;

    // pobieramy dane jezeli ich nie mamy
    protected function getCachedData(): array|string
    {
        if ($this->cachedData === null) {
            $this->cachedData = $this->fetchRawData();
        }
        return $this->cachedData;
    }

    abstract protected function fetchRawData(): array|string;
}
