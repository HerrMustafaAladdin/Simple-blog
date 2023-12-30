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

}
