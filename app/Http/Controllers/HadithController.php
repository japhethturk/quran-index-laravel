<?php

namespace App\Http\Controllers;

use App\Helpers\AppData;
use App\Repositories\HadithRepositoriesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class HadithController extends Controller
{
    private $hadithRepository;

    public function __construct(HadithRepositoriesInterface $hadithRepository)
    {
        $this->hadithRepository = $hadithRepository;
    }

    public function hadithes(Request $request)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.the-end-times', [], 'tr'),
            'az' => trans('slug.the-end-times', [], 'az'),
            'ru' => trans('slug.the-end-times', [], 'ru'),
            'en' => trans('slug.the-end-times', [], 'en')
        ]);

        $slugName = config('app.url').$appLocale.'/'.trans('slug.name').'/';

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.the-end-times'),
            'slugName' => $slugName,
//            'names' => $this->hadithRepository->names($langId)
        ];
        return view('hadith.endtimes', array_merge(AppData::getMainData(), $properties));
    }
}
