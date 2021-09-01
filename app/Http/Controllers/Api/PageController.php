<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Package;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(20);
        return response()->json(compact('pages'));
    }

    public function createPage(Request $request)
    {
        return $request;
    }

    public function sectionEditor()
    {
        $this->authorize('admin');
        $header = Section::where('name', 'header')->first();
        $activePackages = Section::where('name', 'homeSlider')->first();
        $packages = Package::latest()->paginate(30);
        $achievement = Section::where('name', 'achievement')->first();
        $review = Section::where('name', 'review')->first();
        $footer = Section::where('name', 'footer')->first();
        return response()->json(compact('header', 'packages', 'activePackages', 'achievement', 'review', 'footer'));
    }

    public function sectionEditorPackage()
    {
        $this->authorize('admin');
        $packages = Package::latest()->paginate(30);
        return response()->json(compact('packages'));
    }

    public function updateHeader(Request $request)
    {
        $this->authorize('admin');
        if (isset($request->image)) {
            if (File::exists($request->oldImage)) {
                unlink($request->oldImage);
            }

            $path = 'images/layouts/header/';
            $name = $path . 'header-logo-' . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
    
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
    
            Image::make($request->image)->save($name);
        } else {
            $name = $request->oldImage;
        }
        $find = Section::where('name', 'header')->first();

        $information = isset($find) ? $find : new Section();
        $information->name = 'header';
        $information->info = json_encode(['phone'=> $request->phone, 'email'=>$request->email, 'image'=>$name]);
        $information->save();
    }

    public function updateAchievement(Request $request)
    {
        $this->authorize('admin');
        foreach ($request->oldAchievementImages as $data) {
            if (File::exists($data)) {
                unlink($data);
            }
        }
        
        $achievements = $request->achievements;
        foreach ($request->newAchievements as $data) {
            $path = 'images/layouts/achievement/';
            $name = $path . Str::random(3) . time() . '.' . explode('/', explode(':', substr($data['image'], 0, strpos($data['image'], ';')))[1])[1];
    
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
    
            Image::make($data['image'])->save($name);
            
            array_push($achievements, ['image'=>$name, 'name'=>$data['name']]);
        }

        $find = Section::where('name', 'achievement')->first();

        $information = isset($find) ? $find : new Section();
        $information->name = 'achievement';
        $information->info = json_encode(['title'=> $request->title, 'subTitle'=>$request->subTitle, 'achievements'=>$achievements]);
        $information->save();
    }

    public function updateReview(Request $request)
    {
        $this->authorize('admin');
        foreach ($request->oldReviewImages as $data) {
            if (File::exists($data)) {
                unlink($data);
            }
        }
        
        $reviews = $request->reviews;
        foreach ($request->newReviews as $data) {
            $path = 'images/layouts/review/';
            $name = $path . Str::random(3) . time() . '.' . explode('/', explode(':', substr($data['image'], 0, strpos($data['image'], ';')))[1])[1];
    
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
    
            Image::make($data['image'])->save($name);
            
            array_push($reviews, ['image'=>$name, 'name'=>$data['name'], 'message'=>$data['message']]);
        }

        $find = Section::where('name', 'review')->first();

        $information = isset($find) ? $find : new Section();
        $information->name = 'review';
        $information->info = json_encode(['title'=> $request->title, 'subTitle'=>$request->subTitle, 'reviews'=>$reviews]);
        $information->save();
    }

    public function updateFooter(Request $request)
    {
        $this->authorize('admin');
        $find = Section::where('name', 'footer')->first();
        if (isset($request->image)) {
            if (File::exists($request->oldImage)) {
                unlink($request->oldImage);
            }

            $path = 'images/layouts/footer/';
            $name = $path . 'footer-logo-' . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
    
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
    
            Image::make($request->image)->save($name);
        } else {
            $name = json_decode($find->info, true)['image'];
        }


        $information = isset($find) ? $find : new Section();
        $information->name = 'footer';
        $information->info = json_encode(['image'=>$name, 'message'=>$request->message, 'newsletterMessage'=> $request->newsletterMessage, 'copyright'=>$request->copyright, 'address'=> $request->address, 'social'=>$request->social, 'phone'=>$request->phone, 'email'=>$request->email]);
        $information->save();
    }

    public function addSliderPackage(Request $request)
    {
        $this->authorize('admin');
        $find = Section::where('name', 'homeSlider')->first();

        $information = isset($find) ? $find : new Section();
        $information->name = 'homeSlider';
        $information->info = json_encode(['id'=>$request->activePackages]);
        $information->save();
    }
}
