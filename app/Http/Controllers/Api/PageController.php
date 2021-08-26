<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Information;
use App\Models\Page;
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
        $achievement = Information::where('name', 'achievement')->first();
        $review = Information::where('name', 'review')->first();
        return response()->json(compact('achievement', 'review'));
    }

    public function updateAchievement(Request $request)
    {
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

        $find = Information::where('name', 'achievement')->first();

        $information = isset($find) ? $find : new Information();
        $information->name = 'achievement';
        $information->info = json_encode(['title'=> $request->title, 'subTitle'=>$request->subTitle, 'achievements'=>$achievements]);
        $information->save();
    }

    public function updateReview(Request $request)
    {
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

        $find = Information::where('name', 'review')->first();

        $information = isset($find) ? $find : new Information();
        $information->name = 'review';
        $information->info = json_encode(['title'=> $request->title, 'subTitle'=>$request->subTitle, 'reviews'=>$reviews]);
        $information->save();
    }
}
