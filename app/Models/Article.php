<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // public function tags()
    // {
    //     return $this->belongsToMany(Tag::class, 'posts_tags');
    // }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function views()
    // {
    //     return $this->hasMany(PostsView::class);
    // }


    // public function comments()
    // {
    //     return $this->hasMany(Comment::class)->with('user:id,name,surname,image')->orderBy('created_at', 'desc');
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class)->with('user:id,name,surname,image')->orderBy('created_at', 'desc');
    // }

}
