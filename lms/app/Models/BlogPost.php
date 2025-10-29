<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function blog()
    {
        return $this->belongsTo(BlogCategory::class, 'blogcat_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id', 'id')->where('status', 1);
    }
}
