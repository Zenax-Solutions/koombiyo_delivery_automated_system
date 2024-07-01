<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;

    protected $table = 'district';

    protected $guarded = [];



    public function cities()
    {
        return $this->hasMany(City::class, 'district_id', 'district_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
