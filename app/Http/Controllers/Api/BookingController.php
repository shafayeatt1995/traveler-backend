<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Token;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->with('package', 'payments')->latest()->paginate(20);
        return response()->json(compact('bookings'));
    }

    public function getBooking($id)
    {
        $this->authorize('authCheck');
        $booking = Booking::where('user_id', Auth::id())->where('id', $id)->with('package', 'payments.package')->first();
        return response()->json(compact('booking'));
    }

    public function checkBooking(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'payment' => 'required',
            'price' => 'required',
            'ticket' => 'required',
        ]);

        $paymentStatus = $request->payment == 'partial' ? ($request->price >= $request->min_booking_amount * $request->ticket ? true: false) : true;

        return response()->json(!$paymentStatus ? ['error'=>'You Must Have to Pay Minimum Booking Amount']:'', !$paymentStatus ? 422 : 200);
    }

    public function submitBooking(Request $request)
    {
        $this->authorize('authCheck');
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'payment' => 'required',
            'price' => 'required',
            'ticket' => 'required',
            'paymentType' => 'required',
        ]);

        $package = Package::where('id', $request->packageId)->first();
        if(!$package->status){

            if($request->paymentType == 'stripe'){
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $token = Token::create([
                    'card' => [
                        'number' => $request->card['number'],
                        'exp_month' => Str::substr($request->card['date'], 5),
                        'exp_year' => Str::substr($request->card['date'], 0, 4),
                        'cvc' => $request->card['cvc'],
                    ]
                ]);

                $customer = Customer::create([
                    'email' => $request->email,
                    'source' => $token,
                ]);

                $charge = Charge::create([
                    'customer' => $customer->id,
                    'amount' => (($request->payment == 'full' ? ($request->discount == null ? $request->price : $request->discount) : $request->price) * $request->ticket) * 100,
                    'currency' => 'USD',
                ]);
            }

            $booking = new Booking();
            $booking->user_id = Auth::id();
            $booking->package_id = $request->packageId;
            $booking->name = $request->name;
            $booking->email = $request->email;
            $booking->phone = $request->phone;
            $booking->address = $request->address;
            $booking->ticket = $request->ticket;
            $booking->price = $package->price;
            $booking->discount = $package->discount;
            $booking->booking_type = $request->payment == "full" ? true : false;
            $booking->save();

            $payment = new Payment();
            $payment->user_id = Auth::id();
            $payment->package_id = $request->packageId;
            $payment->booking_id = $booking->id;
            $payment->payment_type = $request->paymentType;
            $payment->payment_email = $request->paymentType == 'stripe' ? $request->email : $request->paypal['email'];
            $payment->transaction_number = $request->paymentType == 'stripe' ? $charge->balance_transaction : $request->paypal['transaction'];
            $payment->amount = $request->paymentType == 'stripe' ? $charge->amount_captured / 100 : $request->paypal['amount'];
            $payment->save();

            return response()->json($booking->id);
        }else{
            return response()->json(['error'=> $package->status ? 'Tour Already Complete' : 'Tour Already Start'], 422);
        }
    }

    public function updateBooking(Booking $booking, Request $request)
    {
        $this->authorize('authCheck');
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $booking->name = $request->name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;
        $booking->address = $request->address;
        $booking->save();
    }

    public function partialPayment(Request $request)
    {
        $this->authorize('authCheck');
        $request->validate([
            'packageId' => 'required',
            'bookingId' => 'required',
            'paymentType' => 'required',
            'amount' => 'required',
        ]);

        $package = Package::where('id', $request->packageId)->first();

        if(!$package->status){

            if($request->paymentType == 'stripe'){
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $token = Token::create([
                    'card' => [
                        'number' => $request->card['number'],
                        'exp_month' => Str::substr($request->card['date'], 5),
                        'exp_year' => Str::substr($request->card['date'], 0, 4),
                        'cvc' => $request->card['cvc'],
                    ]
                ]);

                $customer = Customer::create([
                    'email' => Auth::user()->email,
                    'source' => $token,
                ]);

                $charge = Charge::create([
                    'customer' => $customer->id,
                    'amount' => ($request->amount) * 100,
                    'currency' => 'USD',
                ]);
            }

            $payment = new Payment();
            $payment->user_id = Auth::id();
            $payment->package_id = $request->packageId;
            $payment->booking_id = $request->bookingId;
            $payment->payment_type = $request->paymentType;
            $payment->payment_email = $request->paymentType == 'stripe' ? Auth::user()->email : $request->paypal['email'];
            $payment->transaction_number = $request->paymentType == 'stripe' ? $charge->balance_transaction : $request->paypal['transaction'];
            $payment->amount = $request->paymentType == 'stripe' ? $charge->amount_captured / 100 : $request->paypal['amount'];
            $payment->save();
        }else{
            return response()->json(['error'=> $package->status ? 'Tour Already Complete' : 'Tour Already Start'], 422);
        }
    }
}
