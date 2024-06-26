<?php

namespace App\Filament\Resources\Admin\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Admin\OrderResource;
use App\Models\Branch;
use App\Models\Product;
use App\Services\koombiyoApi;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected $cart = [];

    protected $description = '';

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        foreach ($data['description'] as $key => $value) {
            $this->cart[$value['product_id']] = [
                'product_id' => $value['product_id'],
                'size' => $value['size'],
                'quantity' => $value['quantity'],
            ];

            $product = Product::find($value['product_id']);

            if ($product) {
                $this->description .= $product->name . ' (' . $value['size'] . ') X ' . $value['quantity'] . ', ';
            }
        }

        $data['description'] = $this->cart;
        $data['order_number'] = $data['waybill_id'];


        $branch = Branch::find($data['branch_id']);

        if (isset($branch->api_key) && $branch->api_enable == true) {

            $koombiyoApi = new koombiyoApi;

            $koombiyoData = [
                'apikey' => $branch->api_key,
                'orderWaybillid' => $data['waybill_id'],
                'orderNo' => $data['waybill_id'],
                'receiverName' => $data['receiver_name'],
                'receiverStreet' => $data['delivery_address'],
                'receiverDistrict' => 10,
                'receiverCity' => 684,
                'receiverPhone' => $data['receiver_phone'],
                'description' => rtrim($this->description, ', '),
                'spclNote' => '',
                'getCod' => $data['cod'],
            ];

            $koombiyoApi->addOrder($koombiyoData);
        }

        return $data;
    }
}
