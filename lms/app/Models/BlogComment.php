<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id', 'id');
    }
}
