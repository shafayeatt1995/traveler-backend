<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use App\Models\Place;
use App\Models\Question;
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
            'duration' => 'required',
            'excluded' => 'required',
            'group_size' => 'required',
            'ticket' => 'required|numeric',
            'images' => 'required',
            'included' => 'required',
            'overview' => 'required',
            'price' => 'required',
            'min_booking_amount' => 'required',
            'return_date' => 'required',
            'start_date' => 'required',
            'tour_plan' => 'required',
            'vehicle' => 'required',
        ]);

        $images = [];
        foreach ($request->images as $image) {
            $slug = Str::slug($request->name) . Str::random(2);
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
        $package->images = json_encode($images);
        $package->address = $request->address;
        $package->duration = $request->duration;
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
            'duration' => 'required',
            'excluded' => 'required',
            'group_size' => 'required',
            'ticket' => 'required|numeric',
            'images' => 'required_without:new_images',
            'new_images' => 'required_without:images',
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
        $package->images = json_encode($images);
        $package->address = $request->address;
        $package->duration = $request->duration;
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
        $package = Package::where('slug', $slug)->with('category', 'user', 'questions', 'bookings')->first();
        if(isset($package)){
            $questions = Question::where('package_id', $package->id)->with('user')->latest()->paginate(20);
            $popularPackage = Package::inRandomOrder()->take(5)->get();
            return response()->json(compact('package', 'popularPackage', 'questions'));
        } else {
            return response()->json(['package'=> null, 'questions'=> null, 'popularPackage'=> null, ], 200);
        }
    }

    public function bookingPackage()
    {
        $packages = Package::where('user_id', Auth::id())->with('bookings.payments')->paginate(20);
        return response()->json(compact('packages'));
    }

    public function packageStatus(Package $package, Request $request)
    {
        $package->status = $request->status;
        $package->save();
    }
}
