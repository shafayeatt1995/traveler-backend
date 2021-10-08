<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $subscribers = Subscribe::latest()->paginate(20);
        return response()->json(compact('subscribers'));
    }

    public function createSubscriber(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:"subscribes"|email'
        ]);

        $subscribe = new Subscribe();
        $subscribe->email = $request->email;
        $subscribe->save();
    }

    public function deleteSubscriber(Subscribe $subscribe)
    {
        $this->authorize('admin');
        $subscribe->delete();
    }
}
