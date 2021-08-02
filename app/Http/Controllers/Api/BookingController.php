<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Token;

class BookingController extends Controller
{
    public function makePayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
            $token = Token::create([
                'card' => [
                    'number' => '',
                    'exp_month' => '',
                    'exp_year' => '',
                    'cvc' => '',
                ]
            ]);

            $customer = Customer::create([
                'email' => '',
                'source' => '',
            ]);

            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => 'amount' * 100,
                'currency' => '',
            ]);
    }
}
