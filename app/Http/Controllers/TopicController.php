<?php

namespace App\Http\Controllers;

use App\Repositories\QuranRepositoriesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Helpers\AppData;
use App\Helpers\Helper;

class TopicController extends Controller
{
    
    private $quranRepository;

    public function __construct(QuranRepositoriesInterface $quranRepository)
    {
        $this->quranRepository = $quranRepository;
    }

    public function topics(Request $request)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.quran-by-topics', [], 'tr'),
            'az' => trans('slug.quran-by-topics', [], 'az'),
            'ru' => trans('slug.quran-by-topics', [], 'ru'),
            'en' => trans('slug.quran-by-topics', [], 'en')
        ]);

        $slugPhrase = config('app.url').$appLocale.'/'.trans('slug.phrases').'/';

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.quran-by-topics'),
            'slugPhrase' => $slugPhrase,
            'topics' => $this->quranRepository->topics($langId)
        ];
        return view('topic.topics', array_merge(AppData::getMainData(), $properties));
    }


    public function phrases(Request $request, $topicSlug, $topicId)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.phrases', [], 'tr'),
            'az' => trans('slug.phrases', [], 'az'),
            'ru' => trans('slug.phrases', [], 'ru'),
            'en' => trans('slug.phrases', [], 'en')
        ]);

        $slugVerse = config('app.url').$appLocale.'/'.trans('slug.phrase').'/';

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.quran-by-topics'),
            'slugVerse' => $slugVerse,
            'topic' => $this->quranRepository->topic($langId, $topicId),
            'phrases' => $this->quranRepository->phrases($langId, $topicId)
        ];
        return view('topic.phrases', array_merge(AppData::getMainData(), $properties));
    }

    public function phrase(Request $request, $topicSlug, $topicId, $phraseSlug, $phraseId)
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
            'tr' => trans('slug.phrase', [], 'tr').'/'.$topicSlug.'/'.$topicId.'/'.$phraseSlug.'/'.$phraseId,
            'az' => trans('slug.phrase', [], 'az').'/'.$topicSlug.'/'.$topicId.'/'.$phraseSlug.'/'.$phraseId,
            'ru' => trans('slug.phrase', [], 'ru').'/'.$topicSlug.'/'.$topicId.'/'.$phraseSlug.'/'.$phraseId,
            'en' => trans('slug.phrase', [], 'en').'/'.$topicSlug.'/'.$topicId.'/'.$phraseSlug.'/'.$phraseId
        ]);

        $slugVerse = config('app.url').$appLocale.'/'.trans('slug.chapter').'/';

        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.quran-by-topics'),
            'slugVerse' => $slugVerse,
            'phrase' => $this->quranRepository->phrase($langId, $topicId, $phraseId),
            'versesByPhrase' => $this->quranRepository->versesByPhrase($langId, $transId, $topicId, $phraseId)
        ];
        return view('topic.verses', array_merge(AppData::getMainData(), $properties));
    }

}
