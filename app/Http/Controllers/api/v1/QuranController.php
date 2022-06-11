<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\QuranRepositoriesInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuranController extends Controller
{

    private $quranRepository;

    public function __construct(QuranRepositoriesInterface $quranRepository)
    {
        $this->quranRepository = $quranRepository;
    }

    public function autocomplete($lang, $search): JsonResponse
    {
        try {
            $list = $this->quranRepository->searchChapters($lang, $search);
            $autocomplete = collect([]);
            foreach ($list as $item) {
                $url = config('app.url').$lang.'/'.trans('slug.chapter', [], $lang).'/'.$item->chapter_slug.'/'.$item->chapter_id;
                $name = $item->chapter_name;
                $name = str_replace($search,"<b>".$search."</b>", $name);

                $mean =  $item->chapter_mean;

                $autocomplete->push([
                    "url" => $url,
                    "name" => $name,
                    "category" => $mean
                ]);
            }
            $autocomplete->push([
                "url" => config('app.url').$lang.'/'.trans('slug.search', [], $lang).'/'.$search,
                "name" => "-> ".$search,
                "category" => trans('app.search_in_verses', [], $lang)
            ]);
            return response()->json([
                'listItems' => $autocomplete->all()
            ]);
        } catch (\Exception $e) {
            return response()->json(['listItems' => [],]);
        }
    }
}
