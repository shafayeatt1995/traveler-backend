<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use App\Models\Place;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PackageController extends Controller
{
    public function index()
    {
        $this->authorize('adminOrGuide');
        $packages = Package::where('user_id', Auth::id())->with('category')->orderBy('id', 'desc')->paginate(20);
        $categories = Category::orderBy('name')->get();
        $places = Place::orderBy('name')->get();
        return response()->json(compact('packages', 'categories', 'places'));
    }

    public function create(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'category_id' => 'required|numeric',
            'place_id' => 'required|numeric',
            'duration_day' => 'required',
            'duration_night' => 'required',
            'excluded' => 'required',
            'group_size' => 'required',
            'ticket' => 'required|numeric',
            'images' => 'required',
            'thumbnail' => 'required',
            'included' => 'required',
            'overview' => 'required',
            'price' => 'required',
            'min_booking_amount' => 'required',
            'return_date' => 'required',
            'start_date' => 'required',
            'tour_plan' => 'required',
            'vehicle' => 'required',
        ]);

        $slug = Str::slug($request->name) . Str::random(2);
        $path = 'images/package/thumbnail/';
        $thumbnailName = $path . $slug . time() . '.' . explode('/', explode(':', substr($request->thumbnail, 0, strpos($request->thumbnail, ';')))[1])[1];

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        Image::make($request->thumbnail)->fit(450, 300, function($constraint){$constraint->upsize();})->save($thumbnailName);

        $images = [];
        foreach ($request->images as $image) {
            $path = 'images/package/';
            $name = $path . $slug . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            Image::make($image)->save($name);

            array_push($images, $name);
        }

        $package = new Package();
        $package->user_id = Auth::id();
        $package->category_id = $request->category_id;
        $package->place_id = $request->place_id;
        $package->name = $request->name;
        $package->slug = Str::slug($request->name) . Str::random(2);
        $package->thumbnail = $thumbnailName;
        $package->images = json_encode($images);
        $package->address = $request->address;
        $package->duration_day = $request->duration_day;
        $package->duration_night = $request->duration_night;
        $package->excluded = json_encode($request->excluded);
        $package->included = json_encode($request->included);
        $package->group_size = $request->group_size;
        $package->ticket = $request->ticket;
        $package->overview = $request->overview;
        $package->price = $request->price;
        $package->discount = $request->discount;
        $package->min_booking_amount = $request->min_booking_amount;
        $package->start_date = Carbon::parse($request->start_date);
        $package->return_date = Carbon::parse($request->return_date);
        $package->tour_plan = json_encode($request->tour_plan);
        $package->vehicle = $request->vehicle;
        $package->save();
    }

    public function update(Package $package, Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'category_id' => 'required|numeric',
            'place_id' => 'required|numeric',
            'duration_day' => 'required',
            'duration_night' => 'required',
            'excluded' => 'required',
            'group_size' => 'required',
            'ticket' => 'required|numeric',
            'images' => 'required_without:new_images',
            'new_images' => 'required_without:images',
            'thumbnail' => 'required',
            'included' => 'required',
            'overview' => 'required',
            'price' => 'required',
            'min_booking_amount' => 'required',
            'return_date' => 'required',
            'start_date' => 'required',
            'tour_plan' => 'required',
            'vehicle' => 'required',
        ],
        [
            'images.required_without' => 'Image field is required',
            'new_images.required_without' => 'Image field is required'
        ]);

        foreach ($request->delete_images as $image) {
            if (File::exists($image)) {
                unlink($image);
            }
        }

        if(Str::substr($request->thumbnail, -Str::length($package->thumbnail)) !== $package->thumbnail){
            if (File::exists($package->thumbnail)) {
                unlink($package->thumbnail);
            }

            $slug = Str::slug($request->name) . Str::random(2);
            $path = 'images/package/thumbnail/';
            $thumbnailName = $path . $slug . time() . '.' . explode('/', explode(':', substr($request->thumbnail, 0, strpos($request->thumbnail, ';')))[1])[1];

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            Image::make($request->thumbnail)->fit(450, 300, function($constraint){$constraint->upsize();})->save($thumbnailName);
        }else{
            $thumbnailName = $package->thumbnail;
        }

        $images = $request->images;
        foreach ($request->new_images as $image) {
            $slug = Str::slug($request->name) . Str::random(2);
            $path = 'images/package/';
            $name = $path . $slug . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            Image::make($image)->save($name);

            array_push($images, $name);
        }

        $package->user_id = Auth::id();
        $package->category_id = $request->category_id;
        $package->place_id = $request->place_id;
        $package->name = $request->name;
        $package->thumbnail = $thumbnailName;
        $package->images = json_encode($images);
        $package->address = $request->address;
        $package->duration_day = $request->duration_day;
        $package->duration_night = $request->duration_night;
        $package->excluded = json_encode($request->excluded);
        $package->included = json_encode($request->included);
        $package->group_size = $request->group_size;
        $package->ticket = $request->ticket;
        $package->overview = $request->overview;
        $package->price = $request->price;
        $package->discount = $request->discount;
        $package->min_booking_amount = $request->min_booking_amount;
        $package->start_date = Carbon::parse($request->start_date);
        $package->return_date = Carbon::parse($request->return_date);
        $package->tour_plan = json_encode($request->tour_plan);
        $package->vehicle = $request->vehicle;
        $package->save();
    }

    public function delete(Package $package)
    {
        $this->authorize('admin');
        if (File::exists($package->thumbnail)) {
            unlink($package->thumbnail);
        }
        
        $images = json_decode($package->images, true);
        foreach($images as $image){
            if (File::exists($image)) {
                unlink($image);
            }
        };
        $package->delete();
    }
    
    public function singlePackage($slug)
    {
        $package = Package::where('slug', $slug)->with('category', 'user', 'bookings')->first();
        if(isset($package)){
            $questions = Question::where('package_id', $package->id)->with('user')->latest()->paginate(15);
            $popularPackage = Package::inRandomOrder()->take(5)->get();
            $categories = Category::orderBy('name')->get();
            return response()->json(compact('package', 'popularPackage', 'questions', 'categories'));
        } else {
            return response()->json(['package'=> null, 'questions'=> null, 'popularPackage'=> null, 'categories'=> null, ], 200);
        }
    }
    
    public function bookingPackage()
    {
        $this->authorize('adminOrGuide');
        $packages = Package::where('user_id', Auth::id())->with('bookings.payments')->paginate(20);
        return response()->json(compact('packages'));
    }
    
    public function packageStatus(Package $package, Request $request)
    {
        $this->authorize('adminOrGuide');
        $package->status = $request->status;
        $package->save();
    }

    public function packages()
    {
        $packages = Package::latest()->paginate(10);
        $popular = Package::inRandomOrder()->orderBy('view')->take(5)->get();
        $categories = Category::orderBy('name')->get();
        return response()->json(compact('packages', 'popular', 'categories'));
    }

    public function increment(Package $package)
    {
        $package->view = $package->view + 1;
        $package->save();
    }

    public function userPackages($slug)
    {
        $user = User::where('slug', $slug)->first();
        $packages = Package::latest()->paginate(10);
        $popular = Package::with('user')->orderBy('view')->inRandomOrder()->take(5)->get();
        $categories = Category::orderBy('name')->get();
        
        return response()->json(compact('user', 'packages', 'popular', 'categories'));
    }

    public function categoryPackages($slug)
    {
        $category = Category::where('slug', $slug)->first();
        $packages = Package::latest()->paginate(10);
        $popular = Package::with('user')->orderBy('view')->inRandomOrder()->take(5)->get();
        $categories = Category::orderBy('name')->get();
        
        return response()->json(compact('category', 'packages', 'popular', 'categories'));
    }

    public function searchPackage(Request $request)
    {
        $packages = Package::with('category')
        ->name($request->keyword)
        ->overview($request->keyword)
        ->category($request->categories)
        ->duration($request->durations)
        ->min_price($request->minPrice)
        ->max_price($request->maxPrice)
        ->latest()
        ->paginate(10);
        $popular = Package::with('user')->orderBy('view')->inRandomOrder()->take(5)->get();
        $categories = Category::orderBy('name')->get();
        
        return response()->json(compact('packages', 'popular', 'categories'));
    }

    public function destinationPackage()
    {
        $destinations = Place::with('packages')->withCount('packages')->orderBy('packages_count', 'desc')->get();
        return response()->json(compact('destinations'));
    }
}
