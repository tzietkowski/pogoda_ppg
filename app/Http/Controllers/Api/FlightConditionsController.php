<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherAnalyzerService;
use Illuminate\Http\JsonResponse;
use App\Models\Spot;
use Exception;

/**
 * API controller for flight condition checks.
 *
 * Returns a JSON report based on aggregated weather provider data.
 */
class FlightConditionsController extends Controller
{
    public function __construct(
        private WeatherAnalyzerService $analyzer
    ) {}

    /**
     * Handle the flight conditions endpoint.
     */
    public function check(?Spot $spot = null): JsonResponse
    {
        try {
            if (!$spot) {
                $spot = Spot::first();
            }
            if (!$spot) {
                return response()->json(['error' => 'Brak skonfigurowanych miejscówek w bazie.'], 404);
            }
            $report = $this->analyzer->generateReport($spot);

            return response()->json($report, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Nie udało się pobrać danych pogodowych.',
                'message' => $e->getMessage(),
            ], 503);
        }
    }
}
