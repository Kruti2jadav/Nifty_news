<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Catagories;
use App\Models\Subcatagory;
use App\Models\Users;
use App\Models\Comment;
use App\Models\ArticleMedia;
class Articles extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'author_id',
        'title',
        'slug',
        'short_description',
        'full_content',
        'language',
        'status',
        'is_breaking',
        'is_trending',
        'views',
        'published_at',
        'created_at',
        'updated_at',
        'image'
    ];
 protected $casts = [
        'published_at' => 'datetime',
    ];

    /* Relation: Article belongs to Category */
    public function category()
    {
        return $this->belongsTo(Catagories::class, 'category_id', 'id');
    }
       public function articles()
    {
        return $this->hasMany(Articles::class, 'category_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcatagory::class, 'subcategory_id', 'id');
    }
     public function author()
    {
        return $this->belongsTo(Users::class, 'author_id');
    } 
  public function media()
{
    return $this->hasMany(ArticleMedia::class, 'article_id', 'id');
}
 public function comments() {
        return $this->hasMany(Comment::class);
    }
}
