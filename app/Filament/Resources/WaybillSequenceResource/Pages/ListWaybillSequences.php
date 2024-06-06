<?php

namespace App\Filament\Resources\WaybillSequenceResource\Pages;

use App\Filament\Resources\WaybillSequenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWaybillSequences extends ListRecords
{
    protected static string $resource = WaybillSequenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
