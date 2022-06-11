<?php

namespace App\Http\Controllers;

use App\Repositories\QuranRepositoriesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Helpers\AppData;
use App\Helpers\Helper;

class DictionaryController extends Controller
{

    private $quranRepository;

    public function __construct(QuranRepositoriesInterface $quranRepository)
    {
        $this->quranRepository = $quranRepository;
    }

    
    public function dictionary(Request $request)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.quran-dictionary', [], 'tr'),
            'az' => trans('slug.quran-dictionary', [], 'az'),
            'ru' => trans('slug.quran-dictionary', [], 'ru'),
            'en' => trans('slug.quran-dictionary', [], 'en')
        ]);

        $slugWord = config('app.url').$appLocale.'/'.trans('slug.word').'/';

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.quran-dictionary'),
            'slugWord' => $slugWord,
            'letterWords' => $this->quranRepository->letterWords($langId)
        ];
        return view('dictionary.words', array_merge(AppData::getMainData(), $properties));
    }


    public function word(Request $request, $letterSlug, $letterId, $wordSlug, $wordId)
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
            'tr' => trans('slug.quran-dictionary', [], 'tr'),
            'az' => trans('slug.quran-dictionary', [], 'az'),
            'ru' => trans('slug.quran-dictionary', [], 'ru'),
            'en' => trans('slug.quran-dictionary', [], 'en')
        ]);

        $slugVerse = config('app.url').$appLocale.'/'.trans('slug.chapter').'/';

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.quran-dictionary'),
            'slugVerse' => $slugVerse,
            'word' => $this->quranRepository->word($langId, $letterId, $wordId),
            'versesByWords' => $this->quranRepository->versesByWords($langId, $transId, $letterId, $wordId)
        ];
        return view('dictionary.verses', array_merge(AppData::getMainData(), $properties));
    }

}
