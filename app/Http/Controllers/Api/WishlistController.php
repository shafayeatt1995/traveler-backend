<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function getWishlist($id)
    {
        $wishlists = Wishlist::with('package')->where('user_id', $id)->get();
        return response()->json(compact('wishlists'));
    }

    public function createWishlist($id)
    {
        $wishlist = new Wishlist();
        $wishlist->user_id = Auth::id();
        $wishlist->package_id = $id;
        $wishlist->save();
    }
    
    public function removeWishlist($id)
    {
        $this->authorize('authCheck');
        Wishlist::where('package_id', $id)->where('user_id', Auth::id())->first()->delete();
    }
}
