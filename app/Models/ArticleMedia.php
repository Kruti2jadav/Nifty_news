<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Articles;
class ArticleMedia extends Model
{
     protected $table = 'article_media';
    protected $fillable = [
        'article_id', 'type', 'file_url', 'thumbnail', 'duration', 'size'
    ];
       public $timestamps = false;
       public function article()
    {
        return $this->belongsTo(Articles::class, 'article_id', 'id');
    }
}
