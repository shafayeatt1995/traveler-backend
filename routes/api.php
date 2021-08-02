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

Route::group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers\Api'], function () {
    // Auth Related Route
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('user', 'AuthController@user');

    // Admin Category Related Route
    Route::get('category', 'CategoryController@index');
    Route::post('create-category', 'CategoryController@create');
    Route::post('update-category/{category}', 'CategoryController@update');
    Route::post('delete-category/{category}', 'CategoryController@delete');

    // Admin Tour Package Related Route
    Route::get('package', 'PackageController@index');
    Route::post('create-package', 'PackageController@create');
    Route::post('update-package/{package}', 'PackageController@update');
    Route::post('delete-package/{package}', 'PackageController@delete');
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    // Public Tour Package Related Route
    Route::get('package/{slug}', 'PackageController@singlrPackage');
});
