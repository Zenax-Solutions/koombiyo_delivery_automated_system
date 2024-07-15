<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use GuzzleHttp\Client;

class koombiyoApi
{
    //get barcodes
    public function getAllAllocatedBarcodes($apikey)
    {
        $client = new Client();
        $url = 'https://application.koombiyodelivery.lk/api/Waybils/users';

        try {
            $response = $client->post($url, [
                'form_params' => [
                    'apikey' => $apikey,
                    'limit' => 1,
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                $barcode = json_decode($response->getBody()->getContents(), true);
                return collect($barcode);
            } else {
                return response()->json(['error' => 'Unable to fetch data'], $response->getStatusCode());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching data'], 500);
        }
    }

    //get all district
    public function getAllDistrict($apikey)
    {
        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Districts/users', [
            'apikey' => $apikey,
        ]);

        if ($response->successful()) {
            $districts = $response->json();
            return $districts;
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
            return $cities;
        }

        return response()->json(['error' => 'Unable to fetch data'], $response->status());
    }

    //Add Order to koombiyo

    public function addOrder($data)
    {

        $response = Http::asForm()->post('https://application.koombiyodelivery.lk/api/Addorders/users', $data);

        if ($response->successful()) {
            // No sequences available at all
            $recipient = User::where('email', 'admin@addyourorder.com')->first();

            return Notification::make()
                ->title('Successfully New Order Added to Koombiyo ✅⚡🚚')
                ->success()
                ->send()
                ->sendToDatabase($recipient)->toBroadcast($recipient);
        }

        return Notification::make()
            ->title('Error Adding to Koombiyo System ❌')
            ->danger()
            ->send();
    }
}
