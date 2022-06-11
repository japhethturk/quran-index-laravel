<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QuranRepositories implements QuranRepositoriesInterface
{

    public function chapters($orderBy, $lang)
    {
        $query = DB::table('chapters')
            ->select('chapter_translations.chapter_id', DB::raw("CONCAT(`chapter_translations`.`chapter_slug`, '/', `chapter_translations`.`chapter_id`) AS url"), 'chapter_translations.chapter_name', 'chapter_translations.chapter_mean', 'chapters.chapter_arabic', 'chapters.chapter_type', 'chapters.descent_id', 'chapters.verse_count')
            ->leftJoin('chapter_translations', 'chapters.id', '=', 'chapter_translations.chapter_id');
        if (is_numeric($lang)) {
            $query->where('chapter_translations.lang_id', intval($lang));
        } else {
            $query
                ->where('chapter_translations.lang_code', $lang);
        }

        if ($orderBy == 'descent') {
            $query->orderBy('descent_id', 'asc');
        }
        return $query->get();
    }


    public function chapter($chapterId, $langCode)
    {
        $query = DB::table('chapters')
            // ->select('chapter_id', 'chapter_slug', 'chapter_name')
            ->leftJoin('chapter_translations', 'chapter_translations.chapter_id', '=', 'chapters.id')
            ->where('chapters.id', $chapterId)
            ->where('chapter_translations.lang_code', $langCode);
        return $query->first();
    }


    public function verse($chapterId, $verseId, $transId)
    {
        $query = DB::table('verses')
            ->select('verse_id', 'verse_text')
            ->where('chapter_id', $chapterId)
            ->where('verse_id', $verseId)
            ->where('trans_id', $transId);
        return $query->first();
    }


    public function translationByKey($transKey)
    {
        $query = DB::table('translations')
            ->where('key', $transKey);
        return $query->first();
    }


    public function translationById($transId)
    {
        $query = DB::table('translations')
            ->where('id', $transId);
        return $query->first();
    }


    public function verses($chapterId, $transId): Collection
    {
        $query = DB::table('verses')
            ->select('verse_id', 'verse_text')
            ->where('chapter_id', $chapterId)
            ->where('trans_id', $transId)
            ->orderBy('verse_id', 'asc');

        return $query->get();
    }

    public function juz($juzId, $transId, $lang): Collection
    {
        $query = DB::table('verses')
            ->select('verses.chapter_id', 'chapter_translations.chapter_name', 'verses.verse_id', 'verses.verse_text')
            ->leftJoin('chapter_translations', function ($join) {
                $join->on('chapter_translations.chapter_id', '=', 'verses.chapter_id');
            })
            ->where('chapter_translations.lang_code', '=',$lang)
            ->where('verses.juz_id', '=', $juzId)
            ->where('verses.trans_id', '=', $transId)
//            ->where(function($query) use ($search){
//                $query->whereRaw('MATCH (verses.verse_text) AGAINST (?)', [$search]);
//                $query->orWhere('verses.verse_text','LIKE', '%'.$search.'%');
//            })
            ->orderBy('verses.id', 'asc');

        return $query->get();
    }

    public function search($search, $transId, $lang): Collection
    {
        $query = DB::table('verses')
            ->select('verses.chapter_id', 'chapter_translations.chapter_name', 'verses.verse_id', 'verses.verse_text')
            ->leftJoin('chapter_translations', function ($join) {
                $join->on('chapter_translations.chapter_id', '=', 'verses.chapter_id');
            })
            ->where('chapter_translations.lang_code', '=',$lang)
            ->where('verses.trans_id', '=', $transId)
            ->where(function($query) use ($search){
                $query->whereRaw('MATCH (verses.verse_text) AGAINST (?)', [$search]);
                $query->orWhere('verses.verse_text','LIKE', '%'.$search.'%');
            })
            ->orderBy('verses.id', 'asc')
            ->limit(100);

        return $query->get();
    }


    public function selectedVerses($chapterId, $verseId, $selectedTranslations): Collection
    {
        $query = DB::table('verses')
            ->select('verses.chapter_id', 'verses.verse_id', 'verses.verse_text', 'translations.translator')
            ->leftJoin('translations', 'verses.trans_id', '=', 'translations.id')
            ->where('verses.chapter_id', '=', $chapterId)
            ->where('verses.verse_id', '=', $verseId);

        $query->where(function ($query) use ($selectedTranslations) {
            foreach ($selectedTranslations as $id) {
                $query->orWhere('verses.trans_id', '=', $id);
            }
        });

        return $query->get();
    }


    public function versesWithArabic($chapterId, $transId): Collection
    {
        $query = DB::table('verses')
            ->select('verse_id',
                DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(verse_text  ORDER BY trans_id SEPARATOR  '//'), '//', 1) AS `arabic`"),
                DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(verse_text  ORDER BY trans_id SEPARATOR  '//'), '//', -1) AS `mean`"))
            ->whereRaw('chapter_id = ? AND (trans_id = ? OR trans_id = 4)', [$chapterId, $transId])
            ->groupBy('verse_id')
            ->orderBy('verse_id', 'asc');
        return $query->get();
    }


    public function verseWithArabicAndTransl($chapterId, $verseId, $transId, $translId): Collection
    {
        $query = DB::table('verses')
            ->select('trans_id', 'chapter_id', 'verse_id', 'verse_text')
            ->where('chapter_id', '=', $chapterId)
            ->where('verse_id', '=',  $verseId);

        $query->where(function ($query) use ($transId, $translId) {
            $query->orWhere('trans_id', '=', 4);
            $query->orWhere('trans_id', '=', $translId);
            $query->orWhere('trans_id', '=', $transId);
        });

        $query->orderBy(DB::raw("FIELD(trans_id, '4', '".intval($translId)."', '".intval($transId)."')" ));
        return $query->get();
    }


    public function transliterations(): Collection
    {
        $query = DB::table('translations')->select('id', 'translator', 'key');
        $query->where('type', 'letter');
        return $query->get();
    }


    public function translations($lang): Collection
    {
        $query = DB::table('translations')->select('id', 'lang_name', 'lang_code', 'name', 'translator', 'key');
        $query->where('type', 'meal');

        switch ($lang) {
            case "tr":
                $query->orderByRaw("FIELD(lang_code, 'ru', 'en', 'az', 'tr') DESC");
                break;
            case "az":
                $query->orderByRaw("FIELD(lang_code, 'en', 'ru', 'tr', 'az') DESC");
                break;
            case "en":
                $query->orderByRaw("FIELD(lang_code, 'az', 'ru', 'tr', 'en') DESC");
                break;
            case "ru":
                $query->orderByRaw("FIELD(lang_code, 'tr', 'en', 'az', 'ru') DESC");
            default:
                $query->orderByRaw("FIELD(lang_code, 'ru', 'en', 'az', 'tr') DESC");
        }

        return $query->get();
    }

    public function vocables($selectedVocableLangKeys, $chapterId, $verseId): Collection
    {
        $query = DB::table('vocables')->select('sort_id', 'arabic2 as arabic');

        foreach ($selectedVocableLangKeys as $key) {
            $query->addSelect($key);
        }
        $query->addSelect('root_text');
        $query->where('chapter_id', '=', $chapterId)->where('verse_id', '=', $verseId);
        return $query->get();
    }


    public function letterWords($langId): Collection
    {
        DB::statement('SET session group_concat_max_len=15000;');
        $query = DB::table('letters')->select(
            'letters.letter_id',
            'letters.letter_name',
//            DB::raw("CONCAT('[',
//            GROUP_CONCAT(
//              JSON_OBJECT(
//                'word_id', word_id,
//                'word_name', word_name
//              )
//              ORDER BY `words`.`word_name`
//            ), ']') as words")
            DB::raw("CONCAT('[',
                GROUP_CONCAT(
                CONCAT('{\"word_id\": ', `words`.`word_id` ,', \"word_name\": \"', `words`.`word_name`,'\"}')
                ORDER BY `words`.`word_name`
                ),
                ']') as words ")
        )
            ->leftJoin('words', function ($join) {
                $join->on('words.letter_id', '=', 'letters.letter_id');
            })
            ->where('letters.lang_id', '=', $langId)
            ->where('words.lang_id', '=', $langId)
            ->groupBy('words.letter_id')
            ->orderBy('letters.letter_name');
        // ->orderBy('words.word_name');
        // dd($query->toSql());
        return $query->get();
    }

    public function word($langId, $letterId, $wordId)
    {
        $query = DB::table('words')
            ->select('words.word_id', 'words.letter_id', 'words.word_name', 'letters.letter_name')
            ->join('letters', function ($join) {
                $join->on('letters.letter_id', '=', 'words.letter_id');
            })
            ->where('words.lang_id', '=', $langId)
            ->where('letters.lang_id', '=', $langId)
            ->where('words.letter_id', '=', $letterId)
            ->where('words.word_id', '=', $wordId);
        // dd($query->toSql());
        return $query->first();
    }


    public function versesByWords($langId, $transId, $letterId, $wordId): Collection
    {
        $query = DB::table('index_by_words')
            ->select('verses.chapter_id', 'chapter_translations.chapter_name', 'verses.verse_id', 'verses.verse_text')
            ->leftJoin('verses', function ($join) {
                $join->on('verses.chapter_id', '=', 'index_by_words.chapter_id')
                    ->on('verses.verse_id', '=', 'index_by_words.verse_id');
            })
            ->leftJoin('chapter_translations', function ($join) {
                $join->on('chapter_translations.chapter_id', '=', 'index_by_words.chapter_id');
            })
            ->where('index_by_words.lang_id', '=', $langId)
            ->where('index_by_words.letter_id', '=', $letterId)
            ->where('index_by_words.word_id', '=', $wordId)
            ->where('verses.trans_id', '=', $transId)
            ->where('chapter_translations.lang_id', '=', $langId);

        return $query->get();
    }


    public function topics($langId): Collection
    {
        $query = DB::table('topics')
            ->select('topic_id', 'topic_name')
            ->where('lang_id', '=', $langId)
            ->orderBy('topic_name');

        return $query->get();
    }

    public function topic($langId, $topicId)
    {
        $query = DB::table('topics')
            ->select('topic_id', 'topic_name')
            ->where('lang_id', '=', $langId)
            ->where('topic_id', '=', $topicId);

        return $query->first();
    }


    public function phrases($langId, $topicId): Collection
    {
        $query = DB::table('phrases')
            ->select('topic_id', 'phrase_id', 'phrase_name')
            ->where('lang_id', '=', $langId)
            ->where('topic_id', '=', $topicId)
            ->orderBy('phrase_name');

        return $query->get();
    }

    public function phrase($langId, $topicId, $phraseId)
    {
        $query = DB::table('phrases')
            ->select('phrases.topic_id', 'topics.topic_name', 'phrases.phrase_id', 'phrases.phrase_name')
            ->leftJoin('topics', function ($join) {
                $join->on('topics.topic_id', '=', 'phrases.topic_id');
            })
            ->where('phrases.lang_id', '=', $langId)
            ->where('phrases.topic_id', '=', $topicId)
            ->where('phrases.phrase_id', '=', $phraseId)
            ->where('topics.lang_id', '=', $langId);

        return $query->first();
    }



    public function versesByPhrase($langId, $transId, $topicId, $phraseId): Collection
    {
        $query = DB::table('index_by_phrases')
            ->select('verses.chapter_id', 'chapter_translations.chapter_name', 'verses.verse_id', 'verses.verse_text')
            ->leftJoin('verses', function ($join) {
                $join->on('verses.chapter_id', '=', 'index_by_phrases.chapter_id')
                    ->on('verses.verse_id', '=', 'index_by_phrases.verse_id');
            })
            ->leftJoin('chapter_translations', function ($join) {
                $join->on('chapter_translations.chapter_id', '=', 'index_by_phrases.chapter_id');
            })
            ->where('index_by_phrases.topic_id', '=', $topicId)
            ->where('index_by_phrases.phrase_id', '=', $phraseId)
            ->where('verses.trans_id', '=', $transId)
            ->where('chapter_translations.lang_id', '=', $langId);
        return $query->get();
    }


    public function lives($langId)
    {
        $query = DB::table('lives')
            ->select('life_id', 'life_name')
            ->where('lang_id', '=', $langId)
            ->orderBy('life_name');

        return $query->get();
    }


    public function life($langId, $lifeId)
    {
        $query = DB::table('lives')
            ->select('lives.life_id', 'lives.life_name')
            ->where('lang_id', '=', $langId)
            ->where('life_id', '=', $lifeId);
        return $query->first();
    }

    public function versesByLife($langId, $transId, $lifeId)
    {
        $query = DB::table('index_by_lives')
            ->select('verses.chapter_id', 'chapter_translations.chapter_name', 'verses.verse_id', 'verses.verse_text')
            ->leftJoin('verses', function ($join) {
                $join->on('verses.chapter_id', '=', 'index_by_lives.chapter_id')
                    ->on('verses.verse_id', '=', 'index_by_lives.verse_id');
            })
            ->leftJoin('chapter_translations', function ($join) {
                $join->on('chapter_translations.chapter_id', '=', 'index_by_lives.chapter_id');
            })
            ->where('index_by_lives.life_id', '=', $lifeId)
            ->where('verses.trans_id', '=', $transId)
            ->where('chapter_translations.lang_id', '=', $langId);
        return $query->get();
    }

    public function names($langId)
    {
        $query = DB::table('names')
            ->select('name_id', 'name_text', 'name_description')
            ->where('lang_id', '=', $langId)
            ->orderBy('name_text');

        return $query->get();
    }


    public function name($langId, $lifeId)
    {
        $query = DB::table('names')
            ->select('name_id', 'name_text', 'name_description', 'name_html')
            ->where('lang_id', '=', $langId)
            ->where('name_id', '=', $lifeId);
        return $query->first();
    }


    public function searchChapters($lang, $search): Collection
    {
        $likeChapter = '%'.$search.'%';
        $query = DB::table('chapter_translations')
            ->where('lang_code', '=', $lang);
        $query->where('chapter_name', 'LIKE', $likeChapter);
        $query->orWhere('chapter_mean', 'LIKE', $likeChapter);

        return $query->get();
    }

}
