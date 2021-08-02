<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
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
        $this->authorize('admin');
        $packages = Package::latest()->with('category')->paginate(20);
        $categories = Category::orderBy('name')->get();
        return response()->json(compact('packages', 'categories'));
    }

    public function create(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'category_id' => 'required|numeric',
            'duration' => 'required',
            'excluded' => 'required',
            'group_size' => 'required',
            'images' => 'required',
            'included' => 'required',
            'overview' => 'required',
            'price' => 'required',
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
        $package->name = $request->name;
        $package->slug = Str::slug($request->name) . Str::random(2);
        $package->images = json_encode($images);
        $package->address = $request->address;
        $package->duration = $request->duration;
        $package->excluded = json_encode($request->excluded);
        $package->included = json_encode($request->included);
        $package->group_size = $request->group_size;
        $package->overview = $request->overview;
        $package->price = $request->price;
        $package->discount = $request->discount;
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
            'duration' => 'required',
            'excluded' => 'required',
            'group_size' => 'required',
            'images' => 'required_without:new_images',
            'new_images' => 'required_without:images',
            'included' => 'required',
            'overview' => 'required',
            'price' => 'required',
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
        $package->name = $request->name;
        $package->slug = Str::slug($request->name) . Str::random(2);
        $package->images = json_encode($images);
        $package->address = $request->address;
        $package->duration = $request->duration;
        $package->excluded = json_encode($request->excluded);
        $package->included = json_encode($request->included);
        $package->group_size = $request->group_size;
        $package->overview = $request->overview;
        $package->price = $request->price;
        $package->discount = $request->discount;
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

    public function singlrPackage($slug)
    {
        $package = Package::where('slug', $slug)->with('category', 'user')->first();
        return response()->json(compact('package'));
    }
}
