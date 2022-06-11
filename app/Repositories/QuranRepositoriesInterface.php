<?php

namespace App\Repositories;

interface QuranRepositoriesInterface
{
    public function chapters($orderBy, $lang);

    public function chapter($chapterId, $langCode);

    public function verse($chapterId, $verseId, $transId);

    public function translationByKey($transKey);

    public function translationById($transId);

    public function verses($chapterId, $transId);

    public function juz($juzId, $transId, $lang);

    public function search($search, $transId, $lang);

    public function selectedVerses($chapterId, $verseId, $selectedTranslations);

    public function versesWithArabic($chapterId, $transId);

    public function verseWithArabicAndTransl($chapterId, $verseId, $transId, $translIds);

    public function transliterations();

    public function translations($lang);

    public function vocables($selectedVocableLangKeys, $chapterId, $verseId);

    public function letterWords($langId);

    public function word($langId, $letterId, $wordId);

    public function versesByWords($langId, $transId, $letterId, $wordId);

    public function topics($langId);

    public function topic($langId, $topicId);

    public function phrases($langId, $topicId);

    public function phrase($langId, $topicId, $phraseId);

    public function versesByPhrase($langId, $transId, $topicId, $phraseId);

    public function lives($langId);

    public function versesByLife($langId, $transId, $lifeId);

    public function life($langId, $lifeId);

    public function names($langId);

    public function name($langId, $lifeId);

    public function searchChapters($lang, $search);
}
