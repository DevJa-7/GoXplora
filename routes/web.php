<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Admin
Route::group(['prefix' => config('backpack.base.route_prefix'), 'middleware' => ['admin'], 'namespace' => 'Admin'], function () {


    // Custom Backpack CRUD urls
    Route::crud('module', 'ModuleCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('tag', 'TagCrudController');

    Route::crud('marker', 'MarkerCrudController');
    Route::crud('route', 'RouteCrudController');
    Route::crud('coordinate', 'CoordinateCrudController');
    Route::crud('beacon', 'BeaconCrudController');

    Route::crud('agreement', 'AgreementCrudController');
    Route::crud('agreement-toggle', 'AgreementToggleCrudController');

    Route::crud('survey', 'SurveyQuestionCrudController');
    Route::crud('survey-answer', 'SurveyAnswerCrudController');

    Route::crud('game/question', 'GameQuestionCrudController');
    Route::crud('game/answer', 'GameAnswerCrudController');
    Route::crud('game/ranking', 'GameRankingCrudController');

    // Cache
    Route::post('cache/flush', function () {\Cache::flush();});
    Route::post('cache/config', function () {Artisan::call('config:cache');});
    Route::post('cache/config/clear', function () {Artisan::call('config:clear');});
    Route::post('cache/route', function () {Artisan::call('route:cache');});
    Route::post('cache/route/clear', function () {Artisan::call('route:clear');});
    Route::post('cache/view', function () {Artisan::call('view:cache');});
    Route::post('cache/view/clear', function () {Artisan::call('view:clear');});
    Route::post('maintenance/up', function () {Artisan::call('up');});
    Route::post('maintenance/down', function () {Artisan::call('down', ['--allow' => $_SERVER['REMOTE_ADDR']]);});
});