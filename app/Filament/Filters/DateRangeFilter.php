<?php

namespace App\Filament\Filters;

use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class DateRangeFilter extends Filter
{
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->form([
                DatePicker::make("{$name}_from")->native(false),
                DatePicker::make("{$name}_until")->native(false),
            ])
            ->query(function (Builder $query, array $data) use (&$name): Builder {
                return $query
                    ->when(
                        $data["{$name}_from"],
                        fn (Builder $query, $date): Builder => $query->whereDate($name, '>=', $date),
                    )
                    ->when(
                        $data["{$name}_until"],
                        fn (Builder $query, $date): Builder => $query->whereDate($name, '<=', $date),
                    );
            });
    }
}
