<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class koombiyoApi
{
    //get barcodes
    public function getAllAllocatedBarcodes()
    {
        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Waybils/users', [
            'apikey' => 'qrxirttTJHVomNMaWaOR',
            'limit' => 1,
        ]);

        if ($response->successful()) {
            $barcode = $response->json();
            return collect($barcode);
        }

        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }

    //get all district
    public function getAllDistrict()
    {
        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Districts/users', [
            'apikey' => 'qrxirttTJHVomNMaWaOR',
        ]);

        if ($response->successful()) {
            $districts = $response->json();
            return collect($districts);
        }

        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }

    //get all cities
    public function getAllCities($districtId)
    {
        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Cities/users', [
            'apikey' => 'qrxirttTJHVomNMaWaOR',
            'district_id' => $districtId,
        ]);

        if ($response->successful()) {
            return $response->body();
        }

        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }
}
