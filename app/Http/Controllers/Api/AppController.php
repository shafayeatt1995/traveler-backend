<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Package;
use App\Models\Place;
use App\Models\Section;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function index()
    {
        $slider = Section::where('name', 'homeSlider')->first();
        $packageId = json_decode($slider->info, true)['id'];
        $sliderPackage = Package::whereIn('id', $packageId)->get();
        $packages = Package::latest()->take(9)->get();
        $locationPackages = Place::with('packages')->withCount('packages')->orderBy('packages_count', 'desc')->take(3)->get();
        $achievement = Section::where('name', 'achievement')->first();
        $review = Section::where('name', 'review')->first();
        $posts = Blog::with('user')->withCount('comments')->latest()->take(3)->get();
        return response()->json(compact('sliderPackage', 'packages', 'locationPackages', 'achievement', 'review', 'posts'));
    }

    public function start()
    {
        $paypal = env('PAYPAL_CLIENT_ID');
        $paypalStatus = env('PAYPAL');
        $stripeStatus = env('STRIPE');
        $appName = env('APP_NAME');
        $imgurStatus = env('IMGUR');
        $imgurId = env('IMGUR_CLIENT_ID') !== '' ? env('IMGUR_CLIENT_ID') : null;
        $header = Section::where('name', 'header')->first();
        $footer = Section::where('name', 'footer')->first();
        $breadcrumb = Section::where('name', 'breadcrumb')->first();
        return response()->json(compact('paypal', 'appName', 'header', 'footer', 'imgurId', 'breadcrumb', 'paypalStatus', 'stripeStatus', 'imgurStatus'));
    }
}
