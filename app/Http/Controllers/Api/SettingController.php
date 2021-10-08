<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $app = ['name' => env('APP_NAME'), 'dev' => env('APP_DEBUG'), 'url' => env('APP_URL'), 'frontendUrl' => env('FRONTEND_URL')];
        $paypal = ['status' => env('PAYPAL'), 'client_id' => env('PAYPAL_CLIENT_ID'), 'secret' => env('PAYPAL_SECRET')];
        $stripe = ['status' => env('STRIPE'), 'client_id' => env('STRIPE_CLIENT_ID'), 'secret' => env('STRIPE_SECRET')];
        $imgur = ['status' => env('IMGUR'), 'client_id' => env('IMGUR_CLIENT_ID'), 'secret' => env('IMGUR_SECRET')];
        $database = ['connection' => env('DB_CONNECTION'), 'name' => env('DB_DATABASE'), 'host' => env('DB_HOST'), 'port' => env('DB_PORT'), 'userName' => env('DB_USERNAME'), 'password' => env('DB_PASSWORD')];
        $mail = ['driver' => env('MAIL_MAILER'), 'address' => env('MAIL_FROM_ADDRESS'), 'host' => env('MAIL_HOST'), 'port' => env('MAIL_PORT'), 'userName' => env('MAIL_USERNAME'), 'password' => env('MAIL_PASSWORD'), 'encryption' => env('MAIL_ENCRYPTION')];
        $titleIcon = ['image' => env('TITLE_ICON')];
        $preloader = ['image' => env('PRELOADER')];
        return response()->json(compact('app', 'paypal', 'stripe', 'imgur', 'database', 'mail', 'titleIcon', 'preloader'));
    }

    public function updateApp(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'dev' => 'required',
            'url' => 'required|url',
            'frontendUrl' => 'required|url',
        ]);

        $status = $request->dev ? 'true' : 'false';
        Artisan::call('env:set APP_NAME ' . $request->name);
        Artisan::call('env:set APP_DEBUG ' . $status);
        Artisan::call('env:set APP_URL ' . $request->url);
        Artisan::call('env:set FRONTEND_URL ' . $request->frontendUrl);
        Artisan::call('config:clear');
    }

    public function updatePaypal(Request $request)
    {
        $this->authorize('admin');
        $status = $request->status ? 'true' : 'false';
        $client_id = $request->client_id !== null ? $request->client_id : "\'\'";
        $secret = $request->secret !== null ? $request->secret : "\'\'";
        Artisan::call('env:set PAYPAL ' . $status);
        Artisan::call('env:set PAYPAL_CLIENT_ID ' . $client_id);
        Artisan::call('env:set PAYPAL_SECRET ' . $secret);
        Artisan::call('config:clear');
    }

    public function updateStripe(Request $request)
    {
        $this->authorize('admin');
        $status = $request->status ? 'true' : 'false';
        $client_id = $request->client_id !== null ? $request->client_id : "\'\'";
        $secret = $request->secret !== null ? $request->secret : "\'\'";
        Artisan::call('env:set STRIPE ' . $status);
        Artisan::call('env:set STRIPE_CLIENT_ID ' . $client_id);
        Artisan::call('env:set STRIPE_SECRET ' . $secret);
        Artisan::call('config:clear');
    }

    public function updateImgur(Request $request)
    {
        $this->authorize('admin');
        $status = $request->status ? 'true' : 'false';
        $client_id = $request->client_id !== null ? $request->client_id : "\'\'";
        $secret = $request->secret !== null ? $request->secret : "\'\'";
        Artisan::call('env:set IMGUR ' . $status);
        Artisan::call('env:set IMGUR_CLIENT_ID ' . $client_id);
        Artisan::call('env:set IMGUR_SECRET ' . $secret);
        Artisan::call('config:clear');
    }

    public function updateDatabase(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'connection' => 'required',
            'name' => 'required',
            'host' => 'required',
            'port' => 'required|numeric',
            'userName' => 'required',
        ]);

        Artisan::call('env:set DB_CONNECTION ' . $request->connection);
        Artisan::call('env:set DB_HOST ' . $request->host);
        Artisan::call('env:set DB_PORT ' . $request->port);
        Artisan::call('env:set DB_DATABASE ' . $request->name);
        Artisan::call('env:set DB_USERNAME ' . $request->userName);
        Artisan::call('env:set DB_PASSWORD23 ' . $request->password);
        Artisan::call('config:clear');
    }

    public function updateMail(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'driver' => 'required',
            'address' => 'nullable|email',
            'host' => 'required',
            'port' => 'required|numeric',
            'userName' => 'required',
            'userName' => 'required',
            'encryption' => 'required',
            'password' => 'required',
        ]);

        Artisan::call('env:set MAIL_MAILER ' . $request->driver);
        Artisan::call('env:set MAIL_FROM_ADDRESS ' . $request->address);
        Artisan::call('env:set MAIL_HOST ' . $request->host);
        Artisan::call('env:set MAIL_PORT ' . $request->port);
        Artisan::call('env:set MAIL_USERNAME ' . $request->userName);
        Artisan::call('env:set MAIL_PASSWORD ' . $request->password);
        Artisan::call('env:set MAIL_ENCRYPTION ' . $request->encryption);
        Artisan::call('config:clear');
    }

    public function updateIcon(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'image' => 'required',
        ]);

        if ($request->update) {
            if (File::exists(env('TITLE_ICON'))) {
                unlink(env('TITLE_ICON'));
            }

            $path = 'images/';
            $name = $path . 'icon' . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            Image::make($request->image)->save($name);
        } else {
            $name = env('TITLE_ICON');
        }

        Artisan::call('env:set TITLE_ICON ' . $name);
        Artisan::call('config:clear');
    }

    public function updatePreloader(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'file' => 'required',
        ]);

        if ($request->update) {
            $image = $request->file('file');
            if (File::exists(env('PRELOADER'))) {
                unlink(env('PRELOADER'));
            }
            $tempName = 'preloader.' . $image->getClientOriginalExtension();
            $path = 'images/';
            $name = $path . $tempName;
            $image->move($path, $name);
        } else {
            $name = env('PRELOADER');
        }

        Artisan::call('env:set PRELOADER ' . $name);
        Artisan::call('config:clear');
    }
}
