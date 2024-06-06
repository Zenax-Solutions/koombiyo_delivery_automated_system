<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('branch_id'),
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('waybill_id'),
            ExportColumn::make('order_number'),
            ExportColumn::make('receiver_name'),
            ExportColumn::make('delivery_address'),
            ExportColumn::make('district_id'),
            ExportColumn::make('city_id'),
            ExportColumn::make('receiver_phone'),
            ExportColumn::make('cod'),
            ExportColumn::make('actual_value'),

        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
