<?php

namespace App\Http\Controllers;

use App\Helpers\AppData;
use App\Helpers\Helper;
use App\Repositories\ArticleRepositoriesInterface;
use App\Repositories\CategoryRepositoriesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ArticleController extends Controller
{
    private $categoryRepository;
    private $articleRepositories;

    public function __construct(CategoryRepositoriesInterface $categoryRepository, ArticleRepositoriesInterface $articleRepositories)
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepositories = $articleRepositories;
    }

    public function categories(Request $request)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.articles-of-faith', [], 'tr'),
            'az' => trans('slug.articles-of-faith', [], 'az'),
            'ru' => trans('slug.articles-of-faith', [], 'ru'),
            'en' => trans('slug.articles-of-faith', [], 'en')
        ]);

        $slugName = config('app.url').$appLocale.'/'.trans('slug.name').'/';
        $categories = $this->categoryRepository->all($langId);
        $properties = [
            'languageList' => $languageList,
            'activeMenuSlug' => trans('slug.articles-of-faith'),
            'slugName' => $slugName,
            '$categories' => $categories
        ];
        return view('article.categories', array_merge(AppData::getMainData(), $properties));
    }

}
