<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Post extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'posts';

    protected $fillable = [
        'title',
        'content',
        'author_id',
        'tags',
        'published_at',
    ];

    public function author()
    {
        return $this->belongsTo(BlogUser::class, 'author_id', '_id');
    }
}
