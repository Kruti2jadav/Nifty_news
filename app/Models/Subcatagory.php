<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Catagories;

class Subcatagory extends Model
{
     protected $table = 'subcategories';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'status'
    ];


    /* Relation: Subcategory belongs to Category */
   public function category()
    {
        return $this->belongsTo(Catagories::class, 'category_id', 'id');
    }
    public function articles()
{
    return $this->hasMany(\App\Models\Articles::class, 'subcategory_id');
}

}
