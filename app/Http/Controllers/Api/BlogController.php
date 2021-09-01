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
        $client_id = env('IMGUR_CLIENT_ID') !== '' ? env('IMGUR_CLIENT_ID') : null;
        return response()->json(compact('posts', 'categories', 'client_id'));
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

        $blog = new Blog();
        $blog->user_id = Auth::id();
        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title) . Str::random(2);
        $blog->image = $name;
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

            $path = 'images/blog/';
            $name = $path . Str::random(2) . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            Image::make($request->image)->save($name);
        } else {
            $name = $blog->image;
        }

        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title) . Str::random(2);
        $blog->image = $name;
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
        $blog->delete();
    }

    public function post($slug)
    {
        $post = Blog::where('slug', $slug)->available()->with('user', 'category')->withCount('comments')->first();
        $comments = Comment::where('blog_id', $post->id)->with('user', 'replays.user')->latest()->paginate(15);
        $posts = Blog::with('user')->inRandomOrder()->take(5)->get();
        $categories = Category::orderBy('name')->get();
        return response()->json(compact('post', 'comments', 'posts', 'categories'));
    }

    public function categoryPost($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (isset($category)) {
            $posts = Blog::where('category_id', $category->id)->with('user')->withCount('comments')->inRandomOrder()->paginate(15);
        } else {
            $posts = null;
        }
        return response()->json(compact('category', 'posts'));
    }

    public function userPost($slug)
    {
        $user = User::where('slug', $slug)->first();
        if (isset($user)) {
            $posts = Blog::where('user_id', $user->id)->with('user')->withCount('comments')->inRandomOrder()->paginate(15);
        } else {
            $posts = null;
        }
        
        return response()->json(compact('user', 'posts'));
    }

    public function blogPosts()
    {
        $posts = Blog::with('user')->withCount('comments')->inRandomOrder()->paginate(15);
        return response()->json(compact('posts'));
    }
}
