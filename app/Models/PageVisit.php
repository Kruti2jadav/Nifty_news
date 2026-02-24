<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
 protected $table = 'page_visits';

    protected $fillable = [
        'page',
        'ip_address',
        'user_agent',
        'is_guest',
    ];
}
