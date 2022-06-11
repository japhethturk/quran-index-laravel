<?php

namespace App\Http\Controllers;

use App\Repositories\QuranRepositoriesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Helpers\AppData;
use App\Helpers\Helper;

class NameController extends Controller
{

    private $quranRepository;

    public function __construct(QuranRepositoriesInterface $quranRepository)
    {
        $this->quranRepository = $quranRepository;
    }


    public function names(Request $request)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.names-of-god', [], 'tr'),
            'az' => trans('slug.names-of-god', [], 'az'),
            'ru' => trans('slug.names-of-god', [], 'ru'),
            'en' => trans('slug.names-of-god', [], 'en')
        ]);

        $slugName = config('app.url').$appLocale.'/'.trans('slug.name').'/';

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.names-of-god'),
            'slugName' => $slugName,
            'names' => $this->quranRepository->names($langId)
        ];
        return view('name.names', array_merge(AppData::getMainData(), $properties));
    }


    public function content(Request $request, $nameSlug, $nameId)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.name', [], 'tr').'/'.$nameSlug.'/'.$nameId,
            'az' => trans('slug.name', [], 'az').'/'.$nameSlug.'/'.$nameId,
            'ru' => trans('slug.name', [], 'ru').'/'.$nameSlug.'/'.$nameId,
            'en' => trans('slug.name', [], 'en').'/'.$nameSlug.'/'.$nameId
        ]);

        $slugVerse = config('app.url').$appLocale.'/'.trans('slug.chapter').'/';

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.names-of-god'),
            'slugVerse' => $slugVerse,
            'name' => $this->quranRepository->name($langId, $nameId),
        ];

        return view('name.content', array_merge(AppData::getMainData(), $properties));

    }


}
