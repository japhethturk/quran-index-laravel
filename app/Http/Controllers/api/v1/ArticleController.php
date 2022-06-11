<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepositoriesInterface;
use App\Repositories\ArticleRepositoriesInterface;

class ArticleController extends Controller
{

    private $articlesImagePath = 'images/articles/';
    private $contentImagePath = 'images/content/';

    private $categoryRepository;
    private $articleRepositories;

    public function __construct(CategoryRepositoriesInterface $categoryRepository, ArticleRepositoriesInterface $articleRepositories)
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepositories = $articleRepositories;
    }


    public function index()
    {
        //
    }

    public function paginate(Request $request): JsonResponse
    {
        try {
            $langId =  intval($request->langId);
            $rows = intval($request->rows);
            $first = intval($request->first);
            $filters = json_decode($request['filters'], TRUE);
            $filters = gettype($filters) === 'array' ? $filters : [];
            return response()->json([
                'status' => 'ok',
                'paginate' => $this->articleRepositories->paginate($langId, $first, $rows, $filters, $request['sortField'], $request['sortOrder'])
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function store(Request $request, $langId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|unique:articles|min:3|max:255',
        ]);
        $validator->setAttributeNames([
            'category_id' => trans('translation.category'),
            'name' => trans('translation.name'),
            'slug' => trans('translation.slug'),
        ]);

        if ($validator->fails()) {
            Helper::removeFile($request->image, $this->articlesImagePath);
            return Helper::validateFailResponse($validator->messages()->first());
        }

        try {
            $authUser = Auth::user();
            $request['user_id'] = $authUser->id;
            $categories = $this->categoryRepository->all($langId);
            return response()->json([
                'status' => 'ok',
                'categories' => $this->categoryTreeDesign($categories),
                'message' => Helper::saveSuccessMessage()
            ]);
        } catch (\Exception $e) {
            Helper::removeFile($request->image, $this->articlesImagePath);
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function show($id)
    {

    }


    public function edit($langId, $id): JsonResponse
    {
        try {
            $article = $this->articleRepositories->find($id);
            $categories = $this->categoryRepository->all($langId);

            return response()->json([
                'status' => 'ok',
                'categories' => $this->categoryTreeDesign($categories),
                'article' => $article
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255',
        ]);

        $validator->setAttributeNames([
            'category_id' => trans('translation.category'),
            'name' => trans('translation.name'),
            'slug' => trans('translation.slug'),
        ]);


        if ($validator->fails()) {
            Helper::removeFile($request->image, $this->articlesImagePath);
            return Helper::validateFailResponse($validator->messages()->first());
        }

        try {
            $old_image = $this->articleRepositories->update($id, $request->all());
            if ($old_image && $request->image && $request->image !== $old_image) {
                Helper::removeFile($old_image, $this->articlesImagePath);
            }
            return Helper::saveSuccessResponse();
        } catch (\Exception $e) {
            Helper::removeFile($request->image, $this->articlesImagePath);
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function destroy($id): JsonResponse
    {
        try {
            $removeItem = $this->articleRepositories->find($id);
            $image = $removeItem->image;
            $removeItem->delete();
            Helper::removeFile($image, $this->articlesImagePath);
            return Helper::deleteSuccessResponse();
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    private function categoryTreeDesign($categories)
    {
        return $categories->map(function ($item, $key) {
            return [
                'key' => $item->id,
                'label' => $item->name,
                'children' => $item->children->count() === 0 ? [] : $this->categoryTreeDesign($item->children)
            ];
        });
    }


//    private function getChildCategoryIds($category, $idArray){
//        array_push($idArray, $category->id);
//        if ($category->children !== null) {
//            foreach ($category->children as $child) {
//                $idArray = $this->getChildCategoryIds($child, $idArray);
//            }
//        }
//        return $idArray;
//    }

    public function uploadCk(Request $request)
    {
        $fileName = $request->file('upload')->getClientOriginalName();
        $request->upload->move(public_path($this->contentImagePath), $fileName);
        $URL = env("APP_URL", "");
        return response()->json([
            'file_name' => $fileName,
            'uploaded' => true,
            'url' => $URL . $this->contentImagePath . $fileName
        ]);
    }


    public function upload(Request $request): JsonResponse
    {
        return Helper::upload($request, $this->articlesImagePath);
    }



}
