<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Category;
use App\Models\Option;
use App\Models\Page;
use App\Models\Post;
use App\Models\Slider;
use App\Models\User;
use App\Models\Suggested;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    
    public function index()
    {
        try {
            $view = Option::select('value')->where('key', '=', 'view')->first();
            $categoryCount = Category::select('id')->count();
            $postCount = Post::select('id')->count();
            $pageCount = Page::select('id')->count();
            $sliderCount = Slider::select('id')->count();
            $suggestedCount = Suggested::select('id')->count();
            $userCount = User::select('id')->count();
            
            return response()->json([
                'status' => 'ok',
                'viewCount' => $view->value,
                'categoryCount' => $categoryCount,
                'postCount' => $postCount,
                'pageCount' => $pageCount,
                'sliderCount' => $sliderCount,
                'suggestedCount' => $suggestedCount,
                'userCount' => $userCount,
            ]);

        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }

    
}
