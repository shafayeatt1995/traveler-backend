<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PlaceController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $places = Place::latest()->paginate(20);
        return response()->json(compact('places'));
    }

    public function create(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required|unique:places',
            'image' => 'required',
        ]);

        $slug = Str::slug($request->name);
        $path = 'images/place/';
        $name = $path . $slug . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        Image::make($request->image)->save($name);

        $place = new Place();
        $place->name = $request->name;
        $place->slug = Str::slug($request->name);
        $place->image = $name;
        $place->save();
    }
    
    public function update(Place $place, Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'image' => 'required',
        ]);

        if(Str::substr($request->image, -Str::length($place->image)) !== $place->image){
            if (File::exists($place->image)) {
                unlink($place->image);
            }

            $slug = Str::slug($request->name);
            $path = 'images/place/';
            $name = $path . $slug . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
    
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
    
            Image::make($request->image)->save($name);
        }else{
            $name = $place->image;
        }

        $place->name = $request->name;
        $place->slug = Str::slug($request->name);
        $place->image = $name;
        $place->save();
    }

    public function delete(Place $place)
    {
        $this->authorize('admin');
        if (File::exists($place->image)) {
            unlink($place->image);
        }
        $place->delete();
    }
}
