<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Information;
use App\Models\Package;
use App\Models\Place;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $packages = Package::latest()->take(9)->get();
        $locationPackages = Place::with('packages')->withCount('packages')->orderBy('packages_count', 'desc')->take(3)->get();
        $achievement = Information::where('name', 'achievement')->first();
        $review = Information::where('name', 'review')->first();
        return response()->json(compact('packages', 'locationPackages', 'achievement', 'review'));
    }

    public function start()
    {
        $paypal = env('PAYPAL_CLIENT_ID');
        $appName = env('APP_NAME');
        return response()->json(compact('paypal', 'appName'));
    }
}
