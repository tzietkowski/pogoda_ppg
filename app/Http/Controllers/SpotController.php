<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Spot::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Standaryzacja wejścia: Wymuszamy wielkie litery przed walidacją
        $request->merge([
            'metar_code' => strtoupper($request->input('metar_code', ''))
        ]);

        $validMetarStations = [
            'EPWA', // Warszawa Okęcie
            'EPMO', // Warszawa Modlin
            'EPKK', // Kraków Balice
            'EPGD', // Gdańsk Rębiechowo
            'EPKT', // Katowice Pyrzowice
            'EPPO', // Poznań Ławica
            'EPWR', // Wrocław Strachowice
            'EPSC', // Szczecin Goleniów
            'EPBY', // Bydgoszcz Szwederowo
            'EPRZ', // Rzeszów Jasionka
            'EPLB', // Lublin Świdnik
            'EPLL', // Łódź Lublinek
            'EPZG', // Zielona Góra Babimost
            'EPSY', // Olsztyn-Mazury
            'EPRA', // Radom Sadków
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'metar_code' => ['required', 'string', Rule::in($validMetarStations)], // Nasza whitelista!
        ], [
            'metar_code.in' => 'Podany kod stacji jest nieprawidłowy. Wybierz jeden z obsługiwanych polskich portów (np. EPPO, EPGD).'
        ]);

        // 4. Zapis do bazy
        $spot = Spot::create($validated);

        return response()->json($spot, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
