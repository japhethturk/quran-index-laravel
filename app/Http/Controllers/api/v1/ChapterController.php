<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Dao\ChapterDao;


class ChapterController extends Controller
{

    public function chapters($order_by, $lang): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'chapters' => ChapterDao::chapters($order_by, $lang)
        ]);
    }


}
