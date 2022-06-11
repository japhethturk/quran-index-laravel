<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HadithController;
use App\Http\Controllers\PrayController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\LifeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NameController;
use Illuminate\Support\Facades\Route;


Route::prefix(LaravelLocalization::setLocale())->middleware(['localeSessionRedirect', 'localizationRedirect'])->group(function () {
  $locale = Request::segment(1);

  Route::get('/', [QuranController::class, 'quran']);
  Route::get(trans('slug.chapter', [], $locale) . '/{chapterSlug}/{chapterId}', [QuranController::class, 'verses']);
    Route::get(trans('slug.juz', [], $locale) . '/{chapterId}', [QuranController::class, 'juz']);
  Route::get(trans('slug.compare', [], $locale) . '/{chapterSlug}/{chapterId}/'.trans('slug.verse', [], $locale).'/{verse_id}', [QuranController::class, 'compare']);
  Route::get(trans('slug.search', [], $locale) . '/{search?}', [QuranController::class, 'search']);

  Route::get(trans('slug.quran-dictionary', [], $locale), [DictionaryController::class, 'dictionary']);
  Route::get(trans('slug.word', [], $locale).'/{letterSlug}/{letterId}/{wordSlug}/{wordId}', [DictionaryController::class, 'word']);


  Route::get(trans('slug.quran-by-topics', [], $locale), [TopicController::class, 'topics']);
  Route::get(trans('slug.phrases', [], $locale).'/{topicSlug}/{topicId}', [TopicController::class, 'phrases']);
  Route::get(trans('slug.phrase', [], $locale).'/{topicSlug}/{topicId}/{phraseSlug}/{phraseId}', [TopicController::class, 'phrase']);

  Route::get(trans('slug.life-with-quran', [], $locale), [LifeController::class, 'lives']);
  Route::get(trans('slug.life', [], $locale).'/{lifeSlug}/{lifeId}', [LifeController::class, 'life']);

  Route::get(trans('slug.names-of-god', [], $locale), [NameController::class, 'names']);
  Route::get(trans('slug.name', [], $locale).'/{nameSlug}/{nameId}', [NameController::class, 'content']);

  Route::get(trans('slug.the-end-times', [], $locale), [HadithController::class, 'hadithes']);

  Route::get(trans('slug.articles-of-faith', [], $locale), [ArticleController::class, 'categories']);

  Route::get(trans('slug.prayer-time', [], $locale), [PrayController::class, 'pray']);
  Route::get(trans('slug.location', [], $locale) . '/{country_slug}/{country_id}/{state_slug}/{state_id}/{city_slug}/{city_id}', [PrayController::class, 'location']);
//    http://127.0.0.1:8000/tr/konum/turkey/225/istanbul-province/2170/sisli/108963

});


Route::get('/admin/{name2?}}', [AdminController::class, 'admin']);

Route::get('/admin/{name2?}/{name3?}', [AdminController::class, 'admin']);
