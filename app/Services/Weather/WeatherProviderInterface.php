<?php

declare(strict_types=1);

namespace App\Services\Weather;

interface WeatherProviderInterface
{
    public function getProviderName(): string;

    public function getWindSpeed(): float;

    public function getWindDirection(): int;
}
