<?php

namespace Database\Seeders;

use App\Services\koombiyoApi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $api_key = 'qrxirttTJHVomNMaWaOR';
        $koombiyoApi = new koombiyoApi; // Assuming you have a service for the API

        // Ensure foreign keys are enabled
        //DB::statement('PRAGMA foreign_keys=OFF;');

        for ($i = 1; $i <= 25; $i++) {
            $response = $koombiyoApi->getAllCities($api_key, $i);
            $cities = $response;

            foreach ($cities as $city) {
                // Check if the city already exists
                $exists = DB::table('city')
                    ->where('city_id', $city['city_id'])
                    ->where('district_id', $i)
                    ->exists();

                if (!$exists) {
                    // Insert only if the city does not exist
                    DB::table('city')->insert([
                        'district_id' => $i,
                        'city_id' => $city['city_id'],
                        'city_name' => $city['city_name'],
                    ]);
                }
            }
        }
    }
}
