<?php

use App\Http\Controllers\api\v1\PrayController;
use App\Http\Controllers\api\v1\QuranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\ArticleController;
use App\Http\Controllers\api\v1\HadithController;
// use App\Http\Controllers\api\v1\ChapterController;
// use App\Http\Controllers\api\v1\OptionController;
// use App\Http\Controllers\api\v1\TagController;
// use App\Http\Controllers\api\v1\UserController;
// use App\Http\Controllers\api\v1\DashboardController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('chapters/{order_by}/lang/{lang}', [ChapterController::class, 'chapters']);


Route::get('autocomplete/pray/{lang}/search/{search}', [PrayController::class, 'autocomplete']);
Route::get('nearest/longitude/{longitude}/latitude/{latitude}', [PrayController::class, 'nearest']);


Route::get('autocomplete/chapter/{lang}/search/{search}', [QuranController::class, 'autocomplete']);

// Route::get('option/first', [OptionController::class, 'first']);
// Route::get('option/locales/{lng}/{ns}', [OptionController::class, 'locales']);
// Route::post('option/empty', [OptionController::class, 'empty']);
// Route::get('option/scripts', [OptionController::class, 'scripts']);
Route::get('category/all/table/{langId}', [CategoryController::class, 'allTable']);
Route::get('category/all/tree/{langId}', [CategoryController::class, 'allTree']);
Route::get('post/paginate', [ArticleController::class, 'paginate']);

Route::get('index/all/table/{langId}', [HadithController::class, 'allIndex']);

Route::post('authorize/admin', [AuthController::class, 'authorizeAdmin']);



Route::prefix('secure')->middleware(['auth:api'])->group(function() {
    Route::post('authorize/checkToken', [AuthController::class, 'checkToken']);
    Route::post('authorize/logout', [AuthController::class, 'logout']);
});

Route::prefix('secure')->middleware(['auth:api', 'scope:admin,manager,editor'])->group(function() {
    // Route::get('dashboard', [DashboardController::class, 'index']);

    Route::post('category/{langId}/store', [CategoryController::class, 'store']);
    Route::get('category/{langId}/edit/{id}', [CategoryController::class, 'edit']);
    Route::post('category/update/{id}', [CategoryController::class, 'update']);
    Route::get('category/{langId}/remove/{id}', [CategoryController::class, 'destroy']);

    Route::post('post/upload', [ArticleController::class, 'upload']);
    Route::post('post/{langId}/store', [ArticleController::class, 'store']);
    Route::get('post/{langId}/edit/{id}', [ArticleController::class, 'edit']);
    Route::post('post/update/{id}', [ArticleController::class, 'update']);
    Route::get('post/remove/{id}', [ArticleController::class, 'destroy']);
    Route::get('post/publish/{id}', [ArticleController::class, 'publish']);


    Route::post('index/store', [HadithController::class, 'storeIndex']);
    Route::post('index/update/{id}', [HadithController::class, 'updateIndex']);
    Route::get('index/{langId}/remove/{id}', [HadithController::class, 'destroyIndex']);


    Route::post('hadith/store', [HadithController::class, 'store']);
});


Route::get('hadith/paginate', [HadithController::class, 'paginate']);

Route::prefix('secure')->middleware(['auth:api', 'scope:admin,manager'])->group(function() {
    // Route::get('tag/paginate', [TagController::class, 'paginate']);
    // Route::get('tag/remove/{id}', [TagController::class, 'destroy']);
});


Route::prefix('secure')->middleware(['auth:api', 'scope:admin'])->group(function() {

    // Route::get('manager/paginate', [UserController::class, 'mangerPaginate']);
    // Route::post('manager/upload', [UserController::class, 'upload']);
    // Route::post('manager/store', [UserController::class, 'store']);
    // Route::get('manager/edit/{id}', [UserController::class, 'edit']);
    // Route::get('manager/remove/{id}', [UserController::class, 'destroy']);
    // Route::post('manager/update/{id}', [UserController::class, 'update']);

});


// Route::post('post/uploadCk', [ArticleController::class, 'uploadCk']);
