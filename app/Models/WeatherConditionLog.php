<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Persisted flight condition reports from weather analysis.
 *
 * Stores the final decision, average wind data, and detail payload for later review.
 */
class WeatherConditionLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'is_safe_to_fly',
        'average_wind_ms',
        'warning',
        'details',
    ];

    /**
     * The model attribute type casts.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_safe_to_fly' => 'boolean',
        'average_wind_ms' => 'float',
        'details' => 'array',
    ];
}
