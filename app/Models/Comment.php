<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = "comments";

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'is_active',
        'content'
    ];


    public function getIsActiveAttribute($is_active)
    {
        return $is_active ? __('Active') : __('DeActive');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

}
