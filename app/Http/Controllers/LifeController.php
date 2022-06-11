<?php

namespace App\Http\Controllers;

use App\Repositories\QuranRepositoriesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Helpers\AppData;
use App\Helpers\Helper;

class LifeController extends Controller
{
    
    private $quranRepository;

    public function __construct(QuranRepositoriesInterface $quranRepository)
    {
        $this->quranRepository = $quranRepository;
    }

    public function lives(Request $request)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.life-with-quran', [], 'tr'),
            'az' => trans('slug.life-with-quran', [], 'az'),
            'ru' => trans('slug.life-with-quran', [], 'ru'),
            'en' => trans('slug.life-with-quran', [], 'en')
        ]);

        $slugWord = config('app.url').$appLocale.'/'.trans('slug.life').'/';

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.life-with-quran'),
            'slugWord' => $slugWord,
            'lives' => $this->quranRepository->lives($langId)
        ];
        return view('life.lives', array_merge(AppData::getMainData(), $properties));
    }


    public function life(Request $request, $lifeSlug, $lifeId)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $transKey = $request[trans('slug.translation')];
        $transId = 0;
        $translation = [];
        if ($transKey) {
            $translation = $this->quranRepository->translationByKey($transKey);
            $transId = $translation->id;
        } else {
            $transId = Helper::selectedTransId($appLocale);
            $translation = $this->quranRepository->translationById($transId);
        }

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.life', [], 'tr').'/'.$lifeSlug.'/'.$lifeId,
            'az' => trans('slug.life', [], 'az').'/'.$lifeSlug.'/'.$lifeId,
            'ru' => trans('slug.life', [], 'ru').'/'.$lifeSlug.'/'.$lifeId,
            'en' => trans('slug.life', [], 'en').'/'.$lifeSlug.'/'.$lifeId
        ]);

        $slugVerse = config('app.url').$appLocale.'/'.trans('slug.chapter').'/';

        // dd($this->quranRepository->versesByLife($langId, $transId, $lifeId));

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.life-with-quran'),
            'slugVerse' => $slugVerse,
            'life' => $this->quranRepository->life($langId, $lifeId),
            'versesByLives' => $this->quranRepository->versesByLife($langId, $transId, $lifeId)
        ];
        return view('life.verses', array_merge(AppData::getMainData(), $properties));
    }

}