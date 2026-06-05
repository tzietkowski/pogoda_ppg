<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherConditionLog extends Model
{
    protected $fillable = [
        'status',
        'is_safe_to_fly',
        'average_wind_ms',
        'warning',
        'details',
    ];
    protected function casts(): array
    {
        return [
            'is_safe_to_fly' => 'boolean',
            'average_wind_ms' => 'float',
            'details' => 'array',
        ];
    }
}
