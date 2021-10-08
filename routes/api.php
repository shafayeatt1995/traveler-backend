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
    // Account Related Route
    Route::post('verification/{token}', 'AuthController@verification');
    Route::post('send-verification-mail', 'AuthController@sendVerificationMail');

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
    
    //Wishlist Related Route
    Route::get('wishlist/{id}', 'WishlistController@getWishlist');
    Route::post('create-wishlist/{id}', 'WishlistController@createWishlist');
    Route::post('remove-wishlist/{id}', 'WishlistController@removeWishlist');

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
    Route::get('edit-page', 'PageController@index');
    Route::post('create-page', 'PageController@createPage');
    Route::get('section-editor', 'PageController@sectionEditor');
    Route::post('update-header', 'PageController@updateHeader');
    Route::post('update-achievement', 'PageController@updateAchievement');
    Route::post('update-review', 'PageController@updateReview');
    Route::post('update-footer', 'PageController@updateFooter');
    Route::post('update-breadcrumb', 'PageController@updateBreadcrumb');
    Route::post('add-slider-package', 'PageController@addSliderPackage');
    Route::get('section-editor-package', 'PageController@sectionEditorPackage');
    Route::post('update-about', 'PageController@updateAbout');
    Route::post('update-contact', 'PageController@updateContact');
    Route::post('update-faq', 'PageController@updatefaq');
    
    // Contact Us Page Related Route
    Route::get('get-contact-message', 'ContactMessageController@index');
    Route::post('update-contact-message/{contact_message}', 'ContactMessageController@updateMessage');
    Route::post('delete-contact-message/{contact_message}', 'ContactMessageController@deleteMessage');
    
    // Site Setting Related Route
    Route::get('site-setting', 'SettingController@index');
    Route::post('update-app', 'SettingController@updateApp');
    Route::post('update-paypal', 'SettingController@updatePaypal');
    Route::post('update-stripe', 'SettingController@updateStripe');
    Route::post('update-imgur', 'SettingController@updateImgur');
    Route::post('update-database', 'SettingController@updateDatabase');
    Route::post('update-mail', 'SettingController@updateMail');
    Route::post('update-icon', 'SettingController@updateIcon');
    Route::post('update-preloader', 'SettingController@updatePreloader');
    
    // Admin Dashboard Related Route
    Route::get('admin-dashboard', 'DashboardController@adminDashboard');
    Route::get('admin-booking-details', 'DashboardController@adminBookingDetails');
    Route::get('admin-today-booking-details', 'DashboardController@adminTodayBookingDetails');
    Route::get('admin-available-package', 'DashboardController@adminAvailablePackage');
    Route::get('admin-tour-running-package', 'DashboardController@adminTourRunningPackage');

    // Guide Dashboard Related Route
    Route::get('guide-dashboard', 'DashboardController@guideDashboard');
    Route::get('guide-booking-details', 'DashboardController@guideBookingDetails');
    Route::get('guide-today-booking-details', 'DashboardController@guideTodayBookingDetails');

    // User Dashboard Related Route
    Route::get('user-dashboard', 'DashboardController@userDashboard');

    // Subscriber Related Route
    Route::get('subscriber', 'SubscribeController@index');
    Route::post('delete-subscriber/{subscribe}', 'SubscribeController@deleteSubscriber');
});

//Auth Not Required Route List
Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    // Get Site Ready Information
    Route::get('start', 'AppController@start');
    
    // Home Page Route
    Route::get('home', 'AppController@index');

    // Package Related Route
    Route::get('packages', 'PackageController@packages');
    Route::post('increment-package/{package}', 'PackageController@increment');
    Route::get('user-packages/{slug}', 'PackageController@userPackages');
    Route::get('category-packages/{slug}', 'PackageController@categoryPackages');
    Route::get('destination-package', 'PackageController@destinationPackage');
    Route::post('search-package', 'PackageController@searchPackage');
    
    // Public Tour Package Related Route
    Route::get('package/{slug}', 'PackageController@singlePackage');
    
    // Booking Related Route
    Route::post('check-booking/', 'BookingController@checkBooking');

    // Blog Related Route
    Route::get('blog/{slug}', 'BlogController@post');
    Route::post('increment-blog/{blog}', 'BlogController@increment');
    Route::get('blog-posts', 'BlogController@blogPosts');
    Route::get('category-blog/{slug}', 'BlogController@categoryPost');
    Route::get('user-blog/{slug}', 'BlogController@userPost');
    Route::get('search-blog/{keyword}', 'BlogController@searchPost');

    // Addictional Page Related Route
    Route::get('about', 'PageController@about');
    Route::get('contact', 'PageController@contact');
    Route::get('faq', 'PageController@faq');
    
    // Send Contact Us Message
    Route::post('submit-message', 'ContactMessageController@submitMessage');
    
    // Forget Password Related Password
    Route::post('forget-password', 'AuthController@forgetPassword');
    Route::get('find-reset-link/{token}', 'AuthController@resetLink');
    Route::post('reset-password', 'AuthController@resetPassword');

    // Subscriber Related Route
    Route::post('create-subscriber', 'SubscribeController@createSubscriber');
});
