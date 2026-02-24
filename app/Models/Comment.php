<?php

namespace App\Models;
use App\Models\Users;
use App\Models\Articles;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = ['article_id', 'user_id', 'parent_id', 'content', 'status'];

    public function user() {
        return $this->belongsTo(Users::class);
    }

    public function article() {
        return $this->belongsTo(Articles::class);
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
