<?php

namespace App\Models;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
 protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'language',
    'avatar',
    'remember_token',
];
 public $timestamps = false; 
 public function comments() {
        return $this->hasMany(Comment::class);
    }
}
