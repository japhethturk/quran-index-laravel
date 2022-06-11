<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Ads;
use App\Models\Category;
use App\Models\Option;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OptionController extends Controller
{
    public function first(): JsonResponse
    {
        try {
            $url = env("APP_URL", "");
            
            $option = [
                'app_name' => env('APP_NAME', ""),
                'url' => $url,
                'admin' => '/'. env("APP_ADMIN", "admin") .'/',
                'storage' => $url . env("APP_STORAGE", "images") . '/',
                'api' => $url . 'api/v1/',
                'secure_api' => $url . 'api/secure/',
                'error_image' => $url . 'images/not_image.png',
                'expire_cookie_day' => 7,
                'mobile_width' => 960,
                'max_image_size_mb' => 1
            ];

            return response()->json([
                'status' => 'ok',
                'option' => $option,
            ]);

        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }

    }

    public function locales($lng, $ns)
    {
        $translator = app('translator');
        return $translator->get($ns, [], $lng);
    }


    public function update(Request $request) {
        try {
            $option = Option::where("key", "=", $request->key)->first();
            $option->value = $request->value;
            $option->save();
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }

    public function updateTheme(Request $request) {
        try {

            $option = Option::where("key", "=", 'theme_name')->first();
            $option->value = $request->theme_name;
            $option->save();

            $option = Option::where("key", "=", 'theme_ripple')->first();
            $option->value = $request->theme_ripple;
            $option->save();

            $option = Option::where("key", "=", 'theme_dark')->first();
            $option->value = $request->theme_dark;
            $option->save();

            $option = Option::where("key", "=", 'background_type')->first();
            $option->value = $request->background_type;
            $option->save();

            $option = Option::where("key", "=", 'background_color')->first();
            $option->value = $request->background_color;
            $option->save();

        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function uploadFavicon(Request $request)
    {
        $path = '/';
        $option = Option::where("key", "=", "favicon")->first();
        Helper::removeFile($option->value, $path);
        $fileName = $request->file('image')->getClientOriginalName();
        $option->value = $fileName;
        $option->save();
        return Helper::uploadFav($request, $path);
    }

    public function uploadLogo(Request $request)
    {
        $path = 'images/use/';
        $option = Option::where("key", "=", "logo")->first();
        Helper::removeFile($option->value, $path);
        $fileName = $request->file('image')->getClientOriginalName();
        $option->value = $fileName;
        $option->save();
        return Helper::upload($request, $path);
    }

    public function uploadBackground(Request $request)
    {
        $path = 'images/use/';
        $option = Option::where("key", "=", "background_image")->first();
        Helper::removeFile($option->value, $path);
        return Helper::upload($request, $path);
    }

    public function empty(Request $request)
    {
        return "";
    }

    public function scripts(Request $request) {
        try {
            $scripts = Option::select('type', 'key', 'value')
                ->where('type', '=', 'script')->get();
            return response()->json([
                'status'=>'ok',
                'scripts' => $scripts
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function updateMetaAndSocials(Request $request)
    {
        try {
            $req = $request->all();

            $option = Option::where("key", "=", 'title')->first();
            $option->value = $req['title'];
            $option->save();

            $option = Option::where("key", "=", 'keywords')->first();
            $option->value = $req['keywords'];
            $option->save();

            $option = Option::where("key", "=", 'description')->first();
            $option->value = $req['description'];
            $option->save();

            $option = Option::where("key", "=", 'footer')->first();
            $option->value = $req['footer'];
            $option->save();

            $option = Option::where("key", "=", 'mini_description')->first();
            $option->value = $req['mini_description'];
            $option->save();

            $option = Option::where("key", "=", 'instagram')->first();
            $option->value = $req['instagram'];
            $option->save();

            $option = Option::where("key", "=", 'facebook')->first();
            $option->value = $req['facebook'];
            $option->save();

            $option = Option::where("key", "=", 'twitter')->first();
            $option->value = $req['twitter'];
            $option->save();

            $option = Option::where("key", "=", 'pinterest')->first();
            $option->value = $req['pinterest'];
            $option->save();

            $option = Option::where("key", "=", 'soundcloud')->first();
            $option->value = $req['soundcloud'];
            $option->save();

            $option = Option::where("key", "=", 'flickr')->first();
            $option->value = $req['flickr'];
            $option->save();

            $option = Option::where("key", "=", 'tumblr')->first();
            $option->value = $req['tumblr'];
            $option->save();

            $option = Option::where("key", "=", 'vk')->first();
            $option->value = $req['vk'];
            $option->save();


            return Helper::saveSuccessResponse();
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function updateScripts(Request $request)
    {
        try {
            $req = $request->all();

            $option = Option::where("key", "=", 'header_script')->first();
            $option->value = $req['header_script'];
            $option->save();

            $option = Option::where("key", "=", 'body_top_script')->first();
            $option->value = $req['body_top_script'];
            $option->save();

            $option = Option::where("key", "=", 'body_bottom_script')->first();
            $option->value = $req['body_bottom_script'];
            $option->save();

            $option = Option::where("key", "=", 'footer_script')->first();
            $option->value = $req['footer_script'];
            $option->save();

            $option = Option::where("key", "=", 'fb_app_id')->first();
            $option->value = $req['fb_app_id'];
            $option->save();

            return Helper::saveSuccessResponse();
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


}
