<?php

namespace App\Providers;

use App\Repositories\PrayRepositories;
use App\Repositories\PrayRepositoriesInterface;
use App\Repositories\QuranRepositories;
use App\Repositories\QuranRepositoriesInterface;
use App\Repositories\CategoryRepositories;
use App\Repositories\CategoryRepositoriesInterface;
use App\Repositories\ArticleRepositories;
use App\Repositories\ArticleRepositoriesInterface;
use App\Repositories\HadithRepositories;
use App\Repositories\HadithRepositoriesInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(QuranRepositoriesInterface::class, QuranRepositories::class);
        $this->app->bind(CategoryRepositoriesInterface::class, CategoryRepositories::class);
        $this->app->bind(ArticleRepositoriesInterface::class, ArticleRepositories::class);
        $this->app->bind(HadithRepositoriesInterface::class, HadithRepositories::class);
        $this->app->bind(PrayRepositoriesInterface::class, PrayRepositories::class);
    }
}
