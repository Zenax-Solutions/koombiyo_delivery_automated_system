<?php

// app/Services/WaybillNumberGenerator.php

namespace App\Services;

use App\Models\WaybillSequence;
use Filament\Notifications\Notification;

class WaybillNumberGenerator
{
    public static function generate(): ?int
    {
        // Check if there are any sequences at all
        if (WaybillSequence::count() == 0) {
            Notification::make()
                ->title('No waybill number available. Please add a new sequence.')
                ->danger()
                ->send();
        }

        $sequence = WaybillSequence::where('active', true)->first();

        if (!$sequence) {
            // No active sequence, find the first available sequence and activate it
            $sequence = WaybillSequence::orderBy('start_number')->first();

            if ($sequence) {
                $sequence->active = true;
                $sequence->save();
            } else {
                // No sequences available at all
                Notification::make()
                    ->title('No waybill number available. Please add a new sequence.')
                    ->danger()
                    ->send();
            }
        }

        if ($sequence !== null) {

            $waybillNumber = $sequence->current_number;

            $sequence->current_number++;

            if ($sequence->current_number > $sequence->end_number) {
                $sequence->active = false;
                $sequence->save();

                // Find the next available sequence
                $nextSequence = WaybillSequence::where('start_number', '>', $sequence->end_number)->where('active', true)
                    ->orderBy('start_number')
                    ->first();
                if ($nextSequence) {
                    $nextSequence->active = true;
                    $nextSequence->save();
                }
            } else {
                $sequence->save();
            }

            return $waybillNumber;
        } else {
            Notification::make()
                ->title('No waybill number available. Please add a new sequence.')
                ->danger()
                ->send();

            return 0;
        }
    }
}
