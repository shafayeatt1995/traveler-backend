<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    public function index()
    {
        $this->authorize('adminOrGuide');
        $posts = Blog::where('user_id', Auth::id())->latest()->paginate(15);
        $categories = Category::orderBy('name')->get();
        return response()->json(compact('posts', 'categories'));
    }
    
    public function createPost(Request $request)
    {
        $this->authorize('adminOrGuide');
        $request->validate([
            'title' => 'required|max:100',
            'image' => 'required',
            'post' => 'required',
            'status' => 'required',
            'category_id' => 'required',
        ]);
        
        $path = 'images/blog/';
        $name = $path . Str::random(2) . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
        
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        
        Image::make($request->image)->save($name);

        $thumbnailPath = 'images/blog/thumbnail/';
        $thumbnailName = $thumbnailPath . Str::random(2) . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
        
        if (!File::exists($thumbnailPath)) {
            File::makeDirectory($thumbnailPath, $mode = 0777, true, true);
        }
        
        Image::make($request->image)->fit(450, 300, function($constraint){$constraint->upsize();})->save($thumbnailName);

        $blog = new Blog();
        $blog->user_id = Auth::id();
        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title) . Str::random(2);
        $blog->image = $name;
        $blog->thumbnail = $thumbnailName;
        $blog->post = $request->post;
        $blog->status = $request->status;
        $blog->save();
    }
    
    public function updatePost(Blog $blog, Request $request)
    {
        $this->authorize('adminOrGuide');$request->validate([
            'title' => 'required|max:100',
            'post' => 'required',
            'status' => 'required',
            'category_id' => 'required',
        ]);

        if (Str::substr($request->image, -Str::length($blog->image)) !== $blog->image) {
            if (File::exists($blog->image)) {
                unlink($blog->image);
            }
            if (File::exists($blog->thumbnail)) {
                unlink($blog->thumbnail);
            }

            $path = 'images/blog/';
            $name = $path . Str::random(2) . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            Image::make($request->image)->save($name);

            $thumbnailPath = 'images/blog/thumbnail/';
            $thumbnailName = $thumbnailPath . Str::random(2) . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            
            if (!File::exists($thumbnailPath)) {
                File::makeDirectory($thumbnailPath, $mode = 0777, true, true);
            }
            
            Image::make($request->image)->fit(450, 300, function($constraint){$constraint->upsize();})->save($thumbnailName);
        } else {
            $name = $blog->image;
            $thumbnailName = $blog->thumbnail;
        }

        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title) . Str::random(2);
        $blog->image = $name;
        $blog->thumbnail = $thumbnailName;
        $blog->post = $request->post;
        $blog->status = $request->status;
        $blog->save();
    }
    
    public function deletePost(Blog $blog)
    {
        $this->authorize('adminOrGuide');
        if (File::exists($blog->image)) {
            unlink($blog->image);
        }
        if (File::exists($blog->thumbnail)) {
            unlink($blog->thumbnail);
        }
        $blog->delete();
    }

    public function post($slug)
    {
        $post = Blog::available()->where('slug', $slug)->with('user', 'category')->withCount('comments')->first();
        $comments = Comment::where('blog_id', $post->id)->with('user', 'replays.user')->latest()->paginate(15);
        $posts = Blog::available()->with('user')->inRandomOrder()->take(5)->get();
        $categories = Category::orderBy('name')->get();
        return response()->json(compact('post', 'comments', 'posts', 'categories'));
    }

    public function increment(Blog $blog)
    {
        $blog->view = $blog->view + 1;
        $blog->save();
    }

    public function categoryPost($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (isset($category)) {
            $posts = Blog::available()->where('category_id', $category->id)->with('user')->withCount('comments')->inRandomOrder()->paginate(10);
        } else {
            $posts = null;
        }
        $categories = Category::orderBy('name')->get();
        $popular = Blog::available()->with('user')->orderBy('view')->inRandomOrder()->take(5)->get();
        return response()->json(compact('category', 'posts', 'categories', 'popular'));
    }

    public function userPost($slug)
    {
        $user = User::where('slug', $slug)->first();
        if (isset($user)) {
            $posts = Blog::available()->where('user_id', $user->id)->with('user')->withCount('comments')->inRandomOrder()->paginate(10);
        } else {
            $posts = null;
        }
        $categories = Category::orderBy('name')->get();
        $popular = Blog::available()->with('user')->orderBy('view')->inRandomOrder()->take(5)->get();
        
        return response()->json(compact('user', 'posts', 'categories', 'popular'));
    }

    public function blogPosts()
    {
        $posts = Blog::available()->with('user')->withCount('comments')->inRandomOrder()->paginate(10);
        $categories = Category::orderBy('name')->get();
        $popular = Blog::available()->with('user')->orderBy('view')->inRandomOrder()->take(5)->get();
        return response()->json(compact('posts', 'categories', 'popular'));
    }

    public function searchPost($keyword)
    {
        $posts = Blog::where('title','LIKE', "%$keyword%")->orWhere('post','LIKE', "%$keyword%")->available()->with('user')->withCount('comments')->paginate(10);
        $categories = Category::orderBy('name')->get();
        $popular = Blog::available()->with('user')->orderBy('view')->inRandomOrder()->take(5)->get();
        return response()->json(compact('posts', 'categories', 'popular'));
    }
}
