<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherAnalyzerService;
use Illuminate\Http\JsonResponse;
use Exception;

class FlightConditionsController extends Controller
{
    // Wstrzykujemy nasz Mózg Operacji
    public function __construct(
        private WeatherAnalyzerService $analyzer
    ) {}

    public function check(): JsonResponse
    {
        try {

            $report = $this->analyzer->generateReport();


            return response()->json($report, 200);
        } catch (Exception $e) {

            return response()->json([
                'error' => 'Nie udało się pobrać danych pogodowych.',
                'message' => $e->getMessage()
            ], 503);
        }
    }
}
