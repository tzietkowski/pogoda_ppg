<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Spot;

class SpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Spot::firstOrCreate(
            ['name' => 'Czerwonak'], // Warunek unikalności
            [
                'latitude' => 52.47000,
                'longitude' => 16.98000,
                'metar_code' => 'EPPO',
            ]
        );
    }
}
