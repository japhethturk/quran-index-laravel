<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\HadithRepositoriesInterface;
use App\Helpers\Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class HadithController extends Controller
{
    private $hadithRepository;

    public function __construct(HadithRepositoriesInterface $hadithRepository)
    {
        $this->hadithRepository = $hadithRepository;
    }

    public function allIndex($langId): JsonResponse
    {
        try {
            $indexList = $this->hadithRepository->allIndex($langId);

            return response()->json([
                'status'=>'ok',
                'list' => $this->listTableDesign($indexList)
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }

    public function storeIndex(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1|max:255'
        ]);
        $validator->setAttributeNames([
            'name' => trans('translation.name')
        ]);
        if ($validator->fails()) {
            return Helper::validateFailResponse($validator->messages()->first());
        }
        try {
            $langId = $request->lang_id;
            $this->hadithRepository->insertIndex($request->all());
            $indexList = $this->hadithRepository->allIndex($langId);
            return response()->json([
                'status' => 'ok',
                'list' => $this->listTableDesign($indexList),
                'message' => Helper::saveSuccessMessage()
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }

    public function updateIndex(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), ['name' => 'required|string|min:1|max:255']);
        $validator->setAttributeNames(['name' => trans('translation.name')]);
        if ($validator->fails()) {
            return Helper::validateFailResponse($validator->messages()->first());
        }
        try {
            $this->hadithRepository->updateIndex($id, $request->all());
            $indexList = $this->hadithRepository->allIndex($request->lang_id);
            return response()->json([
                'status' => 'ok',
                'list' => $this->listTableDesign($indexList),
                'message' => Helper::saveSuccessMessage()
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function destroyIndex($langId, $id): JsonResponse
    {
        try {
            if ($this->hadithRepository->existsHadithHIndexBy('hindex_id', $id)) {
                return response()->json([
                    'status'=>'fail',
                    'message' => [
                        'severity' => 'warn',
                        'summary' => trans('translation.warn'),
                        'detail' => trans('translation.delete_have_hadith'),
                        'sticky' => true
                    ],
                ]);
            } if ($this->hadithRepository->existsIndexChildren($id)) {
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
                $this->hadithRepository->deleteIndex($id);
                $indexList = $this->hadithRepository->allIndex($langId);
                return response()->json([
                    'status'=>'ok',
                    'list' => $this->listTableDesign($indexList),
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

    public function paginate(Request $request)
    {
        try {
            $langId =  intval($request->langId);
            $rows = intval($request->rows);
            $first = intval($request->first);
            $filters = json_decode($request['filters'], TRUE);
            $filters = gettype($filters) === 'array' ? $filters : [];
            return response()->json([
                'status' => 'ok',
                'paginate' => $this->hadithRepository->paginate($langId, $first, $rows, $filters, $request['sortField'], $request['sortOrder'])
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'hadith_text' => 'required|string',
            'source' => 'required|string|min:1|max:255',
        ]);
        $validator->setAttributeNames([
            'hadith_text' => trans('translation.hadith_text'),
            'source' => trans('translation.source'),
        ]);
        if ($validator->fails()) {
            return Helper::validateFailResponse($validator->messages()->first());
        }
        try {
            $langId = $request->lang_id;
            $this->hadithRepository->insert($request->all());
            $indexList = $this->hadithRepository->allIndex($langId);
            return response()->json([
                'status' => 'ok',
                'list' => $this->listTableDesign($indexList),
                'message' => Helper::saveSuccessMessage()
            ]);
        } catch (\Exception $e) {
            return Helper::catchExceptionResponse($e->getMessage());
        }
    }


    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }



    private function listTableDesign($categories)
    {
        return $categories->map(function ($item, $key) {
            return [
                'key' => $item->id,
                'data' => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'publish' => $item->publish,
                ],
                'children' => $item->children->count() === 0 ? [] : $this->listTableDesign($item->children)
            ];
        });
    }

}
