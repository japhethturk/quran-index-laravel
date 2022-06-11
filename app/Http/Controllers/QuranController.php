<?php

namespace App\Http\Controllers;

use App\Repositories\QuranRepositoriesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Helpers\AppData;
use App\Helpers\Helper;

class QuranController extends Controller
{

    private $quranRepository;
    private $appLocale;

    public function __construct(QuranRepositoriesInterface $quranRepository)
    {
        $this->quranRepository = $quranRepository;
        $this->appLocale =  App::currentLocale();
    }

    public function quran(Request $request)
    {
        $properties = [
            'lang'=>$this->appLocale,
            'activeMenuSlug' => '',
            'selectedOrderTab' => Helper::getJsCookie('index-chapter-order'),
            'chapterOrderList' => AppData::getChapterOrders(),
            'chaptersBySurah' => $this->quranRepository->chapters('surah', $this->appLocale),
            'chaptersByDescent' => $this->quranRepository->chapters('descent', $this->appLocale),
            'languageList' => AppData::getLanguages(),
            'chaptersByJuz' => AppData::getJuzList($this->appLocale)
        ];
        return view('quran.chapters', array_merge(AppData::getMainData(), $properties));
    }



    public function verses(Request $request, $chapterSlug, $chapterId)
    {
        $transKey = $request[trans('slug.translation')];
        $transId = 0;
        $translation = [];
        if ($transKey) {
            $translation = $this->quranRepository->translationByKey($transKey);
            $transId = $translation->id;
        } else {
            $transKey = Helper::getJsCookie('index-selected-translation-'.$this->appLocale);
            if ($transKey) {
                $translation = $this->quranRepository->translationByKey($transKey);
                $transId = $translation->id;
            } else {
                $transId = Helper::selectedTransId($this->appLocale);
                $translation = $this->quranRepository->translationById($transId);
            }
        }

        $thisChapter = $this->quranRepository->chapter($chapterId, $this->appLocale);
        $lang_slug_end = '/' . $thisChapter->chapter_slug . '/' . $thisChapter->chapter_id;
        $languageList = AppData::getLanguages([
            'tr' => trans('slug.chapter', [], 'tr') . $lang_slug_end,
            'az' => trans('slug.chapter', [], 'az') . $lang_slug_end,
            'ru' => trans('slug.chapter', [], 'ru') . $lang_slug_end,
            'en' => trans('slug.chapter', [], 'en') . $lang_slug_end
        ]);
        $mainData = AppData::getMainData();

        $compareSlug = $mainData["url"].__('slug.compare').'/'.$thisChapter->chapter_slug.'/'.$thisChapter->chapter_id.'/'.__('slug.verse').'/';
        $compareSlugEnd = '?'.__('slug.translation').'='.$translation->key;

        $properties = [
            'activeMenuSlug' => '',
            'thisUrl' => url()->full(),
            'thisChapter' => $thisChapter,
            'thisTranslation' => $translation,
            'chaptersBySurah' => $this->quranRepository->chapters('surah', $this->appLocale),
            'translations' => $this->quranRepository->translations($this->appLocale, []),
            'verses' => $this->quranRepository->verses($chapterId, $transId),
            'languageList' => $languageList,
            'compareSlug' => $compareSlug,
            'compareSlugEnd' => $compareSlugEnd
        ];
        return view('quran.verses', array_merge($mainData, $properties));
    }


    public function compare(Request $request, $chapterSlug, $chapterId, $verseId)
    {

        $compareSlug = config('app.url').$this->appLocale.'/'.trans('slug.compare').'/'.$chapterSlug.'/'.$chapterId.'/'.__('slug.verse').'/';
        $compareSlugEnd = '';

        $transKey = $request[trans('slug.translation')];
        $transId = 0;
        if ($transKey) {
            $translation = $this->quranRepository->translationByKey($transKey);
            $compareSlugEnd = '?'.trans('slug.translation').'='.$transKey;
            $transId = $translation->id;
        } else {
            $transId = Helper::selectedTransId($this->appLocale);
        }


        $thisChapter = $this->quranRepository->chapter($chapterId, $this->appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.compare',[],'tr').'/'.$chapterSlug.'/'.$chapterId.'/'.trans('slug.verse',[],'tr').'/'.$verseId,
            'az' => trans('slug.compare',[],'az').'/'.$chapterSlug.'/'.$chapterId.'/'.trans('slug.verse',[],'az').'/'.$verseId,
            'ru' => trans('slug.compare',[],'ru').'/'.$chapterSlug.'/'.$chapterId.'/'.trans('slug.verse',[],'ru').'/'.$verseId,
            'en' => trans('slug.compare',[],'en').'/'.$chapterSlug.'/'.$chapterId.'/'.trans('slug.verse',[],'en').'/'.$verseId
        ]);

        $transl = ['tr' => 111, 'az' => 111, 'en' => 40, 'ru' => 40];

        $selectedTranslations = Helper::selectedTranslations($this->appLocale);
        $selectedVocableLangKeys = Helper::selectedVocableLangKeys($this->appLocale);
        $verseWithArabicAndTransl = $this->quranRepository->verseWithArabicAndTransl($chapterId, $verseId, $transId, $transl[$this->appLocale]);

        $verseCount = $thisChapter->verse_count;
        $verseId = intval($verseId);
        $nextVerseId = intval($verseId) + 1;
        $previousVerseId  = $verseId - 1;

        $nextUrl = $compareSlug.$nextVerseId.$compareSlugEnd;

        $pagination = [];
        $paginationSide = [
            0 => [
                'url' => $compareSlug.'1'.$compareSlugEnd,
                'disabled' => $verseId == 1,
            ],
            1 => [
                'url' => $compareSlug.$previousVerseId.$compareSlugEnd,
                'disabled' => $verseId == 1,
            ],
            2 => [
                'url' => $compareSlug.$nextVerseId.$compareSlugEnd,
                'disabled' => $verseId == $verseCount,
            ],
            3 => [
                'url' => $compareSlug.$verseCount.$compareSlugEnd,
                'disabled' => $verseId == $verseCount,
            ]
        ];

        $minPaginate = $verseId;
        if($verseCount <= $verseId + 5) {
            $minPaginate = $verseCount - 4;
        }
        $maxPagination =  $minPaginate + 5;

        for ($i=$minPaginate; $i < $maxPagination; $i++) {
            array_push($pagination, [
                'url' => $compareSlug.$i.$compareSlugEnd,
                'active' => $verseId == $i,
                'label' => $i,
            ]);
        }

        $properties = [
            'activeMenuSlug' => '',
            'thisUrl' => url()->full(),
            'pagination' => $pagination,
            'paginationSide' => $paginationSide,
            'thisChapter' => $thisChapter,
            'selectedTranslations' => $selectedTranslations,
            'chaptersBySurah' => $this->quranRepository->chapters('surah', $this->appLocale),
            'translations' => $this->quranRepository->translations($this->appLocale),
            'wordTranslation' => AppData::getWordTranslation(),
            'selectedVerses' => $this->quranRepository->selectedVerses($chapterId, $verseId, $selectedTranslations),
            'vocableLangList' => AppData::vocableLangList(),
            'languageList' => $languageList,
            'selectedVocableLangKeys' => $selectedVocableLangKeys,
            'thisVerseArabic' => $verseWithArabicAndTransl[0],
            'thisVerseTransl' => $verseWithArabicAndTransl[1],
            'thisVerse' => $verseWithArabicAndTransl[2],
            'vocables' => $this->quranRepository->vocables($selectedVocableLangKeys, $chapterId, $verseId)
        ];

        return view('quran.compare', array_merge(AppData::getMainData(), $properties));
    }

    public function juz(Request $request, $juzId)
    {
        $transKey = $request[trans('slug.translation')];
        if ($transKey) {
            $translation = $this->quranRepository->translationByKey($transKey);
            $transId = $translation->id;
        } else {
            $transKey = Helper::getJsCookie('index-selected-translation-'.$this->appLocale);
            if ($transKey) {
                $translation = $this->quranRepository->translationByKey($transKey);
                $transId = $translation->id;
            } else {
                $transId = Helper::selectedTransId($this->appLocale);
            }
        }

        $lang_slug_end = '';
        $languageList = AppData::getLanguages([
            'tr' => trans('slug.chapter', [], 'tr') . $lang_slug_end,
            'az' => trans('slug.chapter', [], 'az') . $lang_slug_end,
            'ru' => trans('slug.chapter', [], 'ru') . $lang_slug_end,
            'en' => trans('slug.chapter', [], 'en') . $lang_slug_end
        ]);

        $verses = $this->quranRepository->juz($juzId, $transId, $this->appLocale);
        $slugVerse = config('app.url').$this->appLocale.'/'.trans('slug.chapter').'/';

        $properties = [
            'lang' => $this->appLocale,
            'activeMenuSlug' => '',
            'verses' => $verses,
            'slugVerse' => $slugVerse,
            'juzId'=>$juzId,
            'languageList' => $languageList
        ];
        return view('quran.juz', array_merge(AppData::getMainData(), $properties));
    }


    public function search(Request $request)
    {
        $search = $request->search;
        $transKey = $request[trans('slug.translation')];
        if ($transKey) {
            $translation = $this->quranRepository->translationByKey($transKey);
            $transId = $translation->id;
        } else {
            $transKey = Helper::getJsCookie('index-selected-translation-'.$this->appLocale);
            if ($transKey) {
                $translation = $this->quranRepository->translationByKey($transKey);
                $transId = $translation->id;
            } else {
                $transId = Helper::selectedTransId($this->appLocale);
            }
        }

        $lang_slug_end = '';
        $languageList = AppData::getLanguages([
            'tr' => trans('slug.chapter', [], 'tr') . $lang_slug_end,
            'az' => trans('slug.chapter', [], 'az') . $lang_slug_end,
            'ru' => trans('slug.chapter', [], 'ru') . $lang_slug_end,
            'en' => trans('slug.chapter', [], 'en') . $lang_slug_end
        ]);

        $verses = collect([]);
        if ($search) {
            $verses = $this->quranRepository->search($search, $transId, $this->appLocale);
        }

        $slugVerse = config('app.url').$this->appLocale.'/'.trans('slug.chapter').'/';

        $properties = [
//            'lang' => $this->appLocale,
            'activeMenuSlug' => '',
            'verses' => $verses,
            'slugVerse' => $slugVerse,
            'search'=>$search,
            'languageList' => $languageList
        ];
        return view('quran.search', array_merge(AppData::getMainData(), $properties));
    }

}
