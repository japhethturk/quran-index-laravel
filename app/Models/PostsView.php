<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsView extends Model
{
    use HasFactory;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public static function createViewLog($post, Request $request) {
        $postsViews= new PostsView();
        $postsViews->post_id = $post->id;
        $postsViews->slug = $post->slug;
        $postsViews->url = $request->url();
        $postsViews->ip = $request->getClientIp();
        $postsViews->agent = $request->header('User-Agent');
        $postsViews->save();
    }

}
