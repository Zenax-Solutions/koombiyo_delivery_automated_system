<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class koombiyoApi
{
    //get barcodes
    public function getAllAllocatedBarcodes($apikey)
    {
        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Waybils/users', [
            'apikey' => $apikey,
            'limit' => 1,
        ]);

        if ($response->successful()) {
            $barcode = $response->json();
            return collect($barcode);
        }

        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }

    //get all district
    public function getAllDistrict($apikey)
    {
        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Districts/users', [
            'apikey' => $apikey,
        ]);

        if ($response->successful()) {
            $districts = $response->json();
            return collect($districts);
        }

        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }

    //get all cities
    public function getAllCities($apikey, $districtId)
    {
        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Cities/users', [
            'apikey' => $apikey,
            'district_id' => $districtId,
        ]);

        if ($response->successful()) {
            $cities = $response->json();
            return collect($cities);
        }

        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }
}
