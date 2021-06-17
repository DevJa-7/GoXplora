<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['namespace' => 'API'], function () {

    // Token
    Route::get('token', 'UserAPIController@getToken');

    // JSONs
    Route::any('json', 'JsonAPIController@getJsons');

    // Auth
    Route::post('login', 'UserAPIController@login');
    Route::post('logout', 'UserAPIController@logout');
    Route::post('register', 'UserAPIController@register');
    Route::post('password/reset', 'UserAPIController@sendResetLinkEmail');

    // Lang
    Route::any('user/lang', 'UserAPIController@setLang');

    // ----------
    // Auth
    Route::group(['middleware' => ['auth:api']], function () {

        // User
        Route::post('user/get', 'UserAPIController@getUser');
        Route::post('user/update', 'UserAPIController@updateProfile');
        Route::post('user/update/password', 'UserAPIController@updatePassword');
        Route::post('user/delete', 'UserAPIController@deleteUser');

        // User Data
        Route::post('user/data/set', 'UserAPIController@setData');
        Route::post('user/data/get', 'UserAPIController@getData');
        Route::post('user/data/remove', 'UserAPIController@removeData');

        // User Favorites
        Route::post('user/favorites', 'UserAPIController@getFavorites');
        Route::post('user/favorites/add', 'UserAPIController@addFavorite');
        Route::post('user/favorites/remove', 'UserAPIController@removeFavorite');

        // Survey
        Route::post('survey/get', 'SurveyAPIController@getSurvey');
        Route::post('survey/save', 'SurveyAPIController@saveSurvey');

        // Modules
        Route::post('modules', 'AppAPIContoller@getModules');

        // Actions
        Route::post('action', 'AppAPIContoller@addAction');

        // Gamifications
        Route::post('game/modules', 'GameAPIController@getModulesStatus');
        Route::post('game/modules/add', 'GameAPIController@addVisitedModules');
        Route::post('game/question', 'GameAPIController@getGameQuestion');
        Route::post('game/answer', 'GameAPIController@setGameAnswer');
        Route::post('game/rankings', 'GameAPIController@getRankings');
    });

});