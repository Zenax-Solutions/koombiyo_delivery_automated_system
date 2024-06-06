<?php

namespace App\Filament\Resources\Admin\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Admin\OrderResource;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected $cart = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        foreach ($data['description'] as $key => $value) {
            $this->cart[$value['product_id']] = [
                'product_id' => $value['product_id'],
                'size' => $value['size'],
                'quantity' => $value['quantity'],
            ];
        }


        $data['description'] = $this->cart;

        return $data;
    }
}
