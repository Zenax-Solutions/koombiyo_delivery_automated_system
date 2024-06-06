<?php

namespace App\Filament\Resources\WaybillSequenceResource\Pages;

use App\Filament\Resources\WaybillSequenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWaybillSequence extends EditRecord
{
    protected static string $resource = WaybillSequenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
