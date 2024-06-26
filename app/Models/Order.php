<?php

namespace App\Models;

use App\Services\WaybillNumberGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Notifications\Notification;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $guarded = [];

    protected $casts = [
        'description' => 'array',
    ];


    // protected static function booted()
    // {
    //     static::creating(function ($order) {
    //         // Check if the sequence table is empty
    //         if (WaybillSequence::count() == 0) {
    //             Notification::make()
    //                 ->title('No waybill number available. Please add a new sequence.')
    //                 ->danger()
    //                 ->send();
    //         }

    //         // Generate the waybill number if sequences exist
    //         $waybillNumber = WaybillNumberGenerator::generate();

    //         if ($waybillNumber === null) {

    //             Notification::make()
    //                 ->title('No waybill number available. Please add a new sequence.')
    //                 ->danger()
    //                 ->send();

    //             //throw new \Exception('No waybill number available. Please add a new sequence.');
    //         }

    //         $order->waybill_id = $waybillNumber;
    //     });
    // }


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
