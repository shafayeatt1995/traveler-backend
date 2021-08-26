<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 3)->whereNotNull('guide_request')->latest()->paginate(20);
        return response()->json(compact('users'));
    }

    public function status(User $user, Request $request)
    {
        $user->guide_request = null;
        $user->role_id = $request->approve == true ? 2 : 3;
        $user->save();
    }

    public function guides()
    {
        $guides = User::where('role_id', 2)->withCount('packages')->orderBy('packages_count', 'desc')->paginate(15);
        return response()->json(compact('guides'));
    }
}
