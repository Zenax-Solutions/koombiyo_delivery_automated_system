<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        
        $api_key = 'qrxirttTJHVomNMaWaOR';
        $koombiyoApi = app('App\Services\KoombiyoApi'); // Assuming you have a service for the API

      
            $response = $koombiyoApi->getAllDistrict($api_key);
            $districts = $response;

            foreach ($districts as $district) {
                DB::table('district')->insert([
                    'district_name' => $district['district_name'],
                    'district_id' => $district['district_id'],
                ]);
            }
        
    }
}
