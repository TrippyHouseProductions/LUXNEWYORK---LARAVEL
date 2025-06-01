<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BlogUser extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'users'; // Same as your blog's MongoDB users collection

    protected $fillable = [
        'name',
        'email',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id', '_id');
    }
}
