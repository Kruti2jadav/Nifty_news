<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subcatagory;

class Catagories extends Model
{
     protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'is_trending',
        'status'
    ];


    /* Relation: Category has many Subcategories */
      public function subcategories()
    {
        return $this->hasMany(Subcatagory::class, 'category_id', 'id');
    }
}
