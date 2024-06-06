<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branch';

    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
