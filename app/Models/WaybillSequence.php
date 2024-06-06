<?php

namespace App\Models;

use App\Services\WaybillNumberGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaybillSequence extends Model
{
    use HasFactory;

    protected $guarded = [];
}
