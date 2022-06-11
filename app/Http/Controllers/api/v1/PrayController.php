<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Repositories\PrayRepositoriesInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PrayController extends Controller
{

    private $prayRepository;

    public function __construct(PrayRepositoriesInterface $prayRepository)
    {
        $this->prayRepository = $prayRepository;
    }

    public function autocomplete($lang, $search): JsonResponse
    {
        try {
            $list = $this->prayRepository->searchCities($search);
            $autocomplete = collect([]);
            foreach ($list as $item) {
                $url = config('app.url').$lang.'/'.trans('slug.location', [], $lang).'/'.Str::slug($item->country_name).'/'.$item->country_id.'/'
                    .Str::slug($item->state_name).'/'.$item->state_id.'/'
                    .Str::slug($item->city_name).'/'.$item->city_id;
                $name = $item->city_name.', '. $item->state_name;
                $name = str_replace($search,"<b>".$search."</b>", $name);

                $autocomplete->push([
                    "url" => $url,
                    "name" => $name,
                    "category" => $item->country_name
                ]);
            }
            return response()->json([
                'listItems' => $autocomplete->all()
            ]);
        } catch (\Exception $e) {
            return response()->json(['data' => [],]);
        }
    }

    public function nearest($longitude, $latitude): JsonResponse
    {
        try {
            $city = $this->prayRepository->nearest($longitude, $latitude);
            $city->city_slug = Str::slug($city->city_name);
            $city->state_slug = Str::slug($city->state_name);
            $city->country_slug = Str::slug($city->country_name);
            return response()->json([
                'city' => $city
            ]);
        } catch (\Exception $e) {
            return response()->json(['city' => [],]);
        }
    }


}
