<?php

namespace Database\Seeders;

use App\Services\koombiyoApi;
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
        $koombiyoApi = new koombiyoApi; // Assuming you have a service for the API


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
