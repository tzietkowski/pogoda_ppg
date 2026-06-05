<?php

declare(strict_types=1);

return [
    'latitude' => env('PPG_LATITUDE', 52.00),
    'longitude' => env('PPG_LONGITUDE', 17.00),
    'metar_station' => env('PPG_METAR_STATION', 'EPPO'),
    'max_safe_wind' => env('MAX_SAFE_WIND', 5.0),
];
