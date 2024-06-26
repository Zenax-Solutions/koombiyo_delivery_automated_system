<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class koombiyoApi
{
    public function getAllAllocatedBarcodes()
    {
        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Waybils/users', [
            'apikey' => 'qrxirttTJHVomNMaWaOR',
            'limit' => 1,
        ]);

        if ($response->successful()) {
            return $response->body();
        }

        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }

    public function getAllDistrict()
    {
        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Districts/users', [
            'apikey' => 'qrxirttTJHVomNMaWaOR',
        ]);

        if ($response->successful()) {
            return $response->body();
        }

        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }
}
