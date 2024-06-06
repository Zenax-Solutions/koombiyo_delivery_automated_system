<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    protected $table = 'province';

    protected $guarded = [];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
