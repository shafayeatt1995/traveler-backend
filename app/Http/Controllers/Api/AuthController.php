<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendVerificationMail;
use App\Mail\VerificationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgetPassword', 'resetLink', 'resetPassword']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:"users"|email',
            'password' => 'required|min:6|max:20|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->slug = Str::slug($request->name) . Str::random(3);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->social_profile = json_encode(['facebook' => '', 'twitter' => '', 'instagram' => '', 'whatsapp' => '']);
        $user->save();
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        $find = DB::table('password_resets')->where('email', $user->email)->first();

        if (isset($user)) {
            if (!isset($find)) {
                $token = Str::random(64);
                DB::table('password_resets')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);

                $data = [
                    'subject' => 'Reset ' .  env('APP_NAME') . ' Account Password',
                    'email' => $request->email,
                    'user' => $user,
                    'token' => $token,
                ];

                Mail::send('forgetPassword', $data, function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['subject']);
                });
            } else {
                $data = [
                    'subject' => 'Reset ' .  env('APP_NAME') . ' Account Password',
                    'email' => $request->email,
                    'user' => $user,
                    'token' => $find->token,
                ];

                Mail::send('forgetPassword', $data, function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['subject']);
                });
            }
        } else {
            return response()->json(['error' => 'User Not Found'], 422);
        }
    }

    public function resetLink($token)
    {
        $find = DB::table('password_resets')->where('token', $token)->first();
        if (!isset($find)) {
            return response()->json(['error' => 'Reset Password Link Not found'], 422);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|max:20|confirmed',
            'password_confirmation' => 'required'
        ]);

        $find = DB::table('password_resets')->where('token', $request->token)->first();
        $user = User::where('email', $find->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        DB::table('password_resets')->where('token', $request->token)->delete();
    }
}
