<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = "posts";

    protected $fillable = [
        'primary_image',
        'user_id',
        'category_id',
        'title',
        'content',
        'is_active'
    ];


    public function tags()
    {
        return $this->belongsToMany(Tag::class,'post_tag');
    }


    public function getIsActiveAttribute($is_active)
    {
        return $is_active ? __('Active') : __('DeActive');
    }

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postTags()
    {
        return $this->hasMany(PostTag::class);
    }
}
