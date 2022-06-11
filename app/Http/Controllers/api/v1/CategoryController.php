<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\Helper;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CategoryRepositoriesInterface;
use App\Repositories\ArticleRepositoriesInterface;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    private $categoryRepository;
    private $articleRepositories;

    public function __construct(CategoryRepositoriesInterface $categoryRepository, ArticleRepositoriesInterface $ArticleRepositories)
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepositories = $ArticleRepositories;
    }

    public function allTable($langId): JsonResponse
    {
        try {
            $categories = $this->categoryRepository->all($langId);

            return response()->json([
                'status'=>'ok',
                'categories' => $this->categoryTableDesign($categories)
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function allTree($langId): JsonResponse
    {
        try {
            $categories = $this->categoryRepository->all($langId);
            // $categories = Category::select('id', 'name', 'slug')->whereNull('parent_id')->orderBy('created_at', 'desc')->get();
            return response()->json([
                'status'=>'ok',
                'categories' => $this->categoryTreeDesign($categories)
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function create()
    {
        //
    }


    public function store(Request $request, $langId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|unique:categories|min:3|max:255',
        ]);

        $validator->setAttributeNames([
            'name' => trans('translation.name'),
            'slug' => trans('translation.slug'),
        ]);

        if ($validator->fails()) {
            return Helper::validateFailResponse($validator->messages()->first());
        }

        try {
            $authUser = Auth::user();
            $request['user_id'] = $authUser->id;
            $this->categoryRepository->insert($request->all());
            $categories = $this->categoryRepository->all($langId);
            return response()->json([
                'status' => 'ok',
                'categories' => $this->categoryTreeDesign($categories),
                'message' => Helper::saveSuccessMessage()
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($langId, $id): JsonResponse
    {
        try {
            $category = $this->categoryRepository->find($id);
            $categories = $this->categoryRepository->allWithoutThis($id, $langId);

            return response()->json([
                'status'=>'ok',
                'categories' => $this->categoryTreeDesignEdit($categories, intval($id)),
                'category' => $category
            ]);
        }  catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255',
        ]);

        $validator->setAttributeNames([
            'name' => trans('translation.name'),
            'slug' => trans('translation.slug'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>'fail',
                'message' => Helper::message('warn', $validator->messages()->first())
            ]);
        }

        try {
            $this->categoryRepository->update($id, $request->all());
            return Helper::saveSuccessResponse();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => [
                    'severity' => 'error',
                    'summary' => trans('translation.error'),
                    'detail' => trans('translation.catch_exception'),
                    'sticky' => true
                ],
                'detail'=> $e->getMessage(),
            ]);
        }
    }


    public function destroy($langId, $id): JsonResponse
    {
        try {
            if ($this->articleRepositories->existsBy('category_id', $id)) {
                return response()->json([
                    'status'=>'fail',
                    'message' => [
                        'severity' => 'warn',
                        'summary' => trans('translation.warn'),
                        'detail' => trans('translation.delete_have_posts'),
                        'sticky' => true
                    ],
                ]);
            } if ($this->categoryRepository->existsBy('parent_id', $id)) {
                return response()->json([
                    'status'=>'fail',
                    'message' => [
                        'severity' => 'warn',
                        'summary' => trans('translation.warn'),
                        'detail' => trans('translation.delete_have_children'),
                        'sticky' => true
                    ],
                ]);
            } else {
                $this->categoryRepository->delete($id);
                $categories = $this->categoryRepository->all($langId);

                return response()->json([
                    'status'=>'ok',
                    'categories' => $this->categoryTableDesign($categories),
                    'message' => [
                        'severity' => 'success',
                        'summary' => trans('translation.success'),
                        'detail' => trans('translation.delete_success'),
                        'sticky' => true
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => [
                    'severity' => 'error',
                    'summary' => trans('translation.error'),
                    'detail' => trans('translation.catch_exception'),
                    'sticky' => true
                ],
                'detail'=> $e->getMessage(),
            ]);
        }
    }


    private function categoryTableDesign($categories)
    {
        return $categories->map(function ($item, $key) {
            return [
                'key' => $item->id,
                'data' => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'publish' => $item->publish,
                    'date' => $item->created_at
                ],
                'children' => $item->children->count() === 0 ? [] : $this->categoryTableDesign($item->children)
            ];
        });
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

    private function categoryTreeDesignEdit($categories, $exceptionId = 0)
    {
        return $categories->map(function ($item, $key) use ($exceptionId) {
            $children = $item->children->filter(function ($item) use ($exceptionId) {
                return $item->id !== $exceptionId;
            })->values();
            return [
                'key' => $item->id,
                'label' => $item->name,
                'children' => $children->count() === 0 ? [] : $this->categoryTreeDesignEdit($children, $exceptionId)
            ];
        });
    }

}
