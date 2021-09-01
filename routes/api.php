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

// Auth Required Route list
Route::group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers\Api'], function () {
    // Auth Related Route
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('user', 'AuthController@user');
    Route::post('register', 'AuthController@register');
    
    // User Related Controller
    Route::get('users/{role}', 'AuthController@users');
    Route::post('create-user', 'AuthController@createUser');
    Route::post('update-user/{user}', 'AuthController@updateUser');
    Route::post('delete-user/{user}', 'AuthController@deleteUser');
    Route::post('apply-guide', 'AuthController@applyGuide');
    Route::post('update-profile', 'AuthController@updateProfile');
    Route::post('update-password', 'AuthController@updatePassword');

    // Admin Place Related Route
    Route::get('place', 'PlaceController@index');
    Route::post('create-place', 'PlaceController@create');
    Route::post('update-place/{place}', 'PlaceController@update');
    Route::post('delete-place/{place}', 'PlaceController@delete');

    // Admin Category Related Route
    Route::get('category', 'CategoryController@index');
    Route::post('create-category', 'CategoryController@create');
    Route::post('update-category/{category}', 'CategoryController@update');
    Route::post('delete-category/{category}', 'CategoryController@delete');

    // Admin Tour Package Related Route
    Route::get('package', 'PackageController@index');
    Route::get('booking-package', 'PackageController@bookingPackage');
    Route::post('create-package', 'PackageController@create');
    Route::post('update-package/{package}', 'PackageController@update');
    Route::post('delete-package/{package}', 'PackageController@delete');
    Route::post('package-status/{package}', 'PackageController@packageStatus');

    // Tour Package Question Related Route
    Route::get('question/{id}', 'QuestionController@index');
    Route::post('create-question', 'QuestionController@createQuestion');
    Route::post('delete-question/{question}', 'QuestionController@deleteQuestion');
    Route::post('create-replay/{question}', 'QuestionController@createReplay');
    Route::post('delete-replay/{question}', 'QuestionController@deleteReplay');
    
    // Booking Related Route
    Route::get('booking', 'BookingController@index');
    Route::get('get-booking/{id}', 'BookingController@getBooking');
    Route::post('submit-booking', 'BookingController@submitBooking');
    Route::post('update-booking/{booking}', 'BookingController@updateBooking');
    Route::post('partial-payment', 'BookingController@partialPayment');

    // Blog Related Route
    Route::get('post', 'BlogController@index');
    Route::post('create-post', 'BlogController@createPost');
    Route::post('update-post/{blog}', 'BlogController@updatePost');
    Route::post('delete-post/{blog}', 'BlogController@deletePost');

    // Blog Comment Related Route
    Route::get('comment/{id}', 'CommentController@comment');
    Route::post('create-comment', 'CommentController@createComment');
    Route::post('delete-comment/{comment}', 'CommentController@deleteComment');
    Route::post('create-comment-replay', 'CommentController@createCommentReplay');
    Route::post('delete-comment-replay/{comment_replay}', 'CommentController@deleteCommentReplay');
    
    //Guide Related Route
    Route::get('guide-request', 'GuideController@index');
    Route::post('guide-request-status/{user}', 'GuideController@status');
    Route::get('guides', 'GuideController@guides');
    
    // Custom Page & Section Related Page
    Route::get('page', 'PageController@index');
    Route::post('create-page', 'PageController@createPage');
    Route::get('section-editor', 'PageController@sectionEditor');
    Route::post('update-header', 'PageController@updateHeader');
    Route::post('update-achievement', 'PageController@updateAchievement');
    Route::post('update-review', 'PageController@updateReview');
    Route::post('update-footer', 'PageController@updateFooter');
    Route::post('add-slider-package', 'PageController@addSliderPackage');
    Route::get('section-editor-package', 'PageController@sectionEditorPackage');
    
});

//Auth Not Required Route List
Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    // Get Site Ready Information
    Route::get('start', 'AppController@start');
    
    // Home Page Route
    Route::get('home', 'AppController@index');

    // Package Related Route
    Route::get('packages', 'PackageController@packages');
    Route::get('user-packages/{slug}', 'PackageController@userPackages');
    Route::get('category-packages/{slug}', 'PackageController@categoryPackages');
    Route::get('destination-package', 'PackageController@destinationPackage');

    // Public Tour Package Related Route
    Route::get('package/{slug}', 'PackageController@singlePackage');
    
    // Booking Related Route
    Route::post('check-booking/', 'BookingController@checkBooking');
    

    // Blog Related Route
    Route::get('blog/{slug}', 'BlogController@post');
    Route::get('blog-posts', 'BlogController@blogPosts');
    Route::get('category-blog/{slug}', 'BlogController@categoryPost');
    Route::get('user-blog/{slug}', 'BlogController@userPost');
});
