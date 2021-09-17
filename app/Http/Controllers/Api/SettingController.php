<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $app = ['name'=> env('APP_NAME'), 'dev'=> env('APP_DEBUG'), 'url'=> env('APP_URL')];
        $paypal = ['status'=> env('PAYPAL'), 'client_id'=> env('PAYPAL_CLIENT_ID'), 'secret'=> env('PAYPAL_SECRET')];
        $stripe = ['status'=> env('STRIPE'), 'client_id'=> env('STRIPE_CLIENT_ID'), 'secret'=> env('STRIPE_SECRET')];
        $imgur = ['status'=> env('IMGUR'), 'client_id'=> env('IMGUR_CLIENT_ID'), 'secret'=> env('IMGUR_SECRET')];
        $database = ['connection'=> env('DB_CONNECTION'), 'name'=> env('DB_DATABASE'), 'host'=> env('DB_HOST'), 'port'=> env('DB_PORT'), 'userName'=> env('DB_USERNAME'), 'password'=> env('DB_PASSWORD')];
        $mail = ['driver'=> env('MAIL_MAILER'), 'address'=> env('MAIL_FROM_ADDRESS'), 'host'=> env('MAIL_HOST'), 'port'=> env('MAIL_PORT'), 'userName'=> env('MAIL_USERNAME'), 'password'=> env('MAIL_PASSWORD'), 'encryption'=> env('MAIL_ENCRYPTION')];
        return response()->json(compact('app', 'paypal', 'stripe', 'imgur', 'database', 'mail'));
    }

    public function updateApp(Request $request)
    {
        $this->authorize('admin');
        $request->validate([
            'name' => 'required',
            'dev' => 'required',
            'url' => 'required|url',
        ]);

        $status = $request->dev ? 'true' : 'false';
        Artisan::call('env:set APP_NAME ' . $request->name);
        Artisan::call('env:set APP_DEBUG ' . $status);
        Artisan::call('env:set APP_URL ' . $request->url);
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
        Artisan::call('env:set DB_PASSWORD ' . $request->password);
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
    }
}
