<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function users($role)
    {
        $this->authorize('admin');
        $users = User::where('role_id', $role)->with('role')->latest()->paginate(20);
        return response()->json(compact('users'));
    }

    public function createUser(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:"users"',
            'password' => 'required|min:6|max:20|confirmed',
            'userType' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->slug = Str::slug($request->name) . Str::random(3);
        $user->email = $request->email;
        $user->role_id = $request->userType;
        $user->password = Hash::make($request->password);
        $user->social_profile = json_encode(['facebook' => '', 'twitter' => '', 'instagram' => '', 'whatsapp' => '']);
        $user->save();
    }

    public function updateUser(User $user, Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'userType' => 'required',
        ]);

        $user->name = $request->name;
        $user->slug = Str::slug($request->name) . Str::random(3);
        $user->email = $request->email;
        $user->role_id = $request->userType;
        $user->save();
    }

    public function deleteUser(User $user)
    {
        if ($user->image !== 'images/user.png') {
            if (File::exists($user->image)) {
                unlink($user->image);
            }
        }
        $user->delete();
    }

    public function applyGuide(Request $request)
    {
        $this->authorize('user');
        $request->validate([
            'phone' => 'required',
            'message' => 'required|max:500',
        ]);

        $user = Auth::user();
        $user->guide_request = json_encode(['phone' => $request->phone, 'message' => $request->message]);
        $user->save();
    }

    public function updateProfile(Request $request)
    {
        $this->authorize('authCheck');
        $request->validate([
            'name' => 'required',
        ]);

        $user = Auth::user();
        if (Str::substr($request->image, -Str::length($user->image)) !== $user->image) {
            if (File::exists($user->image) && $user->image !== 'images/user.png') {
                unlink($user->image);
            }

            $slug = Str::slug($request->name);
            $path = 'images/user/';
            $name = $path . $slug . time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            Image::make($request->image)->save($name);
        } else {
            $name = $user->image;
        }

        $user->name = $request->name;
        $user->slug = Str::slug($request->name) . Str::random(3);
        $user->image = $name;
        $user->social_profile = json_encode($request->socialProfile);
        $user->save();
    }

    public function updatePassword(Request $request)
    {
        $this->authorize('authCheck');
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|max:20|confirmed',
        ]);

        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->old_password, $hashedPassword)) {
            if (!Hash::check($request->password, $hashedPassword)) {
                $user = Auth::user();
                $user->password = Hash::make($request->password);
                $user->save();
            } else {
                return response()->json(['error' => 'New Password Can not be same as Old Password'], 422);
            }
        } else {
            return response()->json(['error' => 'Current Password not match'], 422);
        }
    }

    public function verification($token)
    {
        $this->authorize('authCheck');
        $userToken = Auth::user()->remember_token;
        if ($userToken == $token) {
            $user = Auth::User();
            $user->email_verified_at = Carbon::now();
            $user->save();
            return response()->json('Account Active Successfully.', 200);
        } else {
            return response()->json(['error' => 'Verification Code Not Matched.'], 422);
        }
    }

    public function sendVerificationMail()
    {
        $this->authorize('authCheck');
        $token = Str::random(25);
        $user = Auth::user();
        if (!isset($user->email_verified_at)) {
            $user->remember_token = isset($user->remember_token) ? $user->remember_token : $token;
            $user->save();

            $data = [
                'subject' => 'Verify ' .  env('APP_NAME') . ' Account',
                'email' => $user->email,
            ];

            Mail::send('verification', $data, function ($message) use ($data) {
                $message->to($data['email'])->subject($data['subject']);
            });
        }
    }
}
