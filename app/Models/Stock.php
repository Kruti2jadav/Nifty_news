<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'symbol', 'name', 'type', 'price', 'change_pts', 'change_percent', 'recorded_at'
    ];
    public $timestamps = false;
}
