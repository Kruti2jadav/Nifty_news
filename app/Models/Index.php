<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Index extends Model
{
     protected $fillable = [
        'symbol', 'name', 'price', 'change_pts', 'change_percent', 'recorded_at'
    ];
    public $timestamps = false;
}
