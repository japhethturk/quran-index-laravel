<?php


namespace App\Helpers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Helper
{

  public static function selectedTranslations($appLocale) {
    $selectedTranslationsString = Helper::getJsCookie('index-'.$appLocale.'-trans-selecteds');
    if ($selectedTranslationsString) {
      $selectedTranslations = explode(',', $selectedTranslationsString);
    } else {
      switch ($appLocale) {
            case "az":
              $selectedTranslations = [5,6,7,132];
              break;
            case "en":
              $selectedTranslations = [20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42];
              break;
            case "ru":
              $selectedTranslations = [78,79,80,85];
              break;
            case "tr":
              $selectedTranslations = [95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119];
              break;
            default:
              $selectedTranslations = [95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119];
          }
    }
    return $selectedTranslations;
  }

  public static function selectedTransId($appLocale) {
    $trans_id= 1;
    $trans_id_cookie = Helper::getJsCookie('index-trans_id');
    if ($trans_id_cookie) {
        $trans_id = $trans_id_cookie;
    } else {
        switch ($appLocale) {
            case "az":
                $trans_id = 5;
                break;
            case "en":
                $trans_id = 20;
                break;
            case "ru":
                $trans_id = 79;
                break;
            case "tr":
                $trans_id = 101;
                break;
            default:
                $trans_id = 101;
        }
    }
    return $trans_id;
  }

  public static function selectedVocableLangKeys($appLocale) {
    $selectedVocableLangKeysString = Helper::getJsCookie('index-'.$appLocale.'-vocable-selecteds');
    if ($selectedVocableLangKeysString) {
      $selectedVocableLangKeys = explode(',', $selectedVocableLangKeysString);
    }else {
      switch ($appLocale) {
            case "az":
              $selectedVocableLangKeys = ['letter_tr', 'turkish'];
              break;
            case "en":
              $selectedVocableLangKeys = ['letter_en','english'];
              break;
            case "ru":
              $selectedVocableLangKeys = ['letter_en', 'russian'];
              break;
            case "tr":
              $selectedVocableLangKeys = ['letter_tr', 'turkish'];
              break;
            default:
              $selectedVocableLangKeys = ['letter_tr', 'turkish'];
          }
    }
    return $selectedVocableLangKeys;
  }

    public static function getJsCookie($key) {
      if(isset($_COOKIE[$key]) ){
          return htmlentities($_COOKIE[$key], 3, 'UTF-8');
      }
      return FALSE;
    }



    public static function getChildrenAllId($category, $idArray){
        array_push($idArray, $category->id);
        if ($category->children !== null) {
            foreach ($category->children as $child) {
                $idArray = Helper::getChildrenAllId($child, $idArray);
            }
        }
        return $idArray;
    }

    public static function categoryMenuDesign($categories, $root = '/')
    {
        return $categories->map(function ($item, $key) use ($root){
            if ($item->children->count() === 0) {
                return [
                    'key' => $item->id,
                    'label' => $item->name,
                    'url' => $root.'category/' .$item->slug
                ];
            } else {
                return [
                    'key' => $item->id,
                    'label' => $item->name,
                    'url' =>  $root.'category/' .$item->slug,
                    'items' => Helper::categoryMenuDesign($item->children, $root),
                    'children' => Helper::categoryMenuDesign($item->children, $root)
                ];
            }
        });
    }

    public static function message(string $severity, string $detail): array
    {
        return [
            'severity' => $severity,
            'summary' => trans('translation.' . $severity),
            'detail' => $detail,
            'sticky' => true
        ];
    }

    public static function saveSuccessMessage(): array
    {
        return [
            'severity' => 'success',
            'summary' => trans('translation.success'),
            'detail' => trans('translation.save_success'),
            'sticky' => true
        ];
    }


    public static function catchExceptionResponse(string $detail): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => [
                'severity' => 'error',
                'summary' => trans('translation.error'),
                'detail' => trans('translation.catch_exception'),
                'sticky' => true
            ],
            'detail' => $detail,
        ]);
    }


    public static function catchExceptionToastResponse(string $detail): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => [
                'type' => 'toast',
                'data' => [
                    'severity' => 'error',
                    'summary' => trans('translation.error'),
                    'detail' => trans('translation.catch_exception'),
                    'life' => 3000
                ]
            ],
            'detail' => $detail,
        ]);
    }


    public static function toastResponse(string $status, string $message, string $detail): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'toast' => [
                'severity' => $status,
                'summary' => trans('translation.' . $status),
                'detail' => $message,
                'life' => 3000
            ],
            'detail' => $detail,
        ]);
    }


    public static function validateFailResponse(string $detail): JsonResponse
    {
        return response()->json([
            'status' => 'fail',
            'message' => [
                'severity' => 'warn',
                'summary' => trans('translation.warn'),
                'detail' => $detail,
                'sticky' => true
            ],
        ]);
    }

    public static function saveSuccessResponse(): JsonResponse
    {
        return response()->json([
            'status'=>'ok',
            'message' => [
                'severity' => 'success',
                'summary' => trans('translation.success'),
                'detail' => trans('translation.save_success'),
                'sticky' => true
            ]
        ]);
    }

    public static function deleteSuccessResponse(): JsonResponse
    {
        return response()->json([
            'status'=>'ok',
            'message' => [
                'severity' => 'success',
                'summary' => trans('translation.success'),
                'detail' => trans('translation.delete_success'),
                'sticky' => true
            ]
        ]);
    }


    public static function upload(Request $request, $imagePath)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image:jpeg,png,jpg,ico,gif,svg|max:' . intval($request->max) * 1024
            ]);

            $validator->setAttributeNames([
                'image' => trans('translation.image')
            ]);

            if ($validator->fails()) {
                return Helper::validateFailResponse($validator->messages()->first());
            }

            $fileName = rand(1000, 9999) .'_'. $request->file('image')->getClientOriginalName();
            $request->image->move(public_path($imagePath), $fileName);

            return response()->json([
                'status' => 'ok',
                'image_name' => $fileName
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }



    public static function uploadFav(Request $request, $imagePath)
    {
        try {
            $fileName = $request->file('image')->getClientOriginalName();
            $request->image->move(public_path($imagePath), $fileName);

            return response()->json([
                'status' => 'ok',
                'image_name' => $fileName
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public static function removeFile($fileName, $imagePath)
    {
        if ($fileName) {
            $path = public_path($imagePath . $fileName);
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }


    public static function getChildCategoryIds($category, $idArray){
        array_push($idArray, $category->id);
        if ($category->children !== null) {
            foreach ($category->children as $child) {
                $idArray = Helper::getChildCategoryIds($child, $idArray);
            }
        }
        return $idArray;
    }


}
