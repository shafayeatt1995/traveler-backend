<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Package;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $bookings = Booking::whereDate('created_at', '>', now()->subMonth())->with('payments')->get();
        $package = Package::whereDate('start_date', '>', now())->where('status', false)->count();
        $tourRunning = Package::whereDate('start_date', '<=', now())->where('status', null)->count();
        $bookingQuantity = 0;
        $bookingAmount = 0;
        foreach ($bookings as $booking) {
            foreach ($booking->payments as $payment) {
                if (isset($payment->amount)) {
                    $bookingAmount += $payment->amount;
                }
            }
            if (isset($booking->ticket)) {
                $bookingQuantity += $booking->ticket;
            }
        };
        $todayBookings = Booking::whereDate('created_at', now())->with('payments')->get();
        $todayQuantity = 0;
        $todayAmount = 0;
        foreach ($todayBookings as $booking) {
            foreach ($booking->payments as $payment) {
                if (isset($payment->amount)) {
                    $todayAmount += $payment->amount;
                }
            }
            if (isset($booking->ticket)) {
                $todayQuantity += $booking->ticket;
            }
        };
        $bookingChart = Booking::where('created_at', '>=', now()->subMonth())
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('SUM(ticket) as ticket')
            ));
        $destinationPackage = Place::select('name')->withCount('runningPackages')->get();
        $topGuide = User::where('role_id', 2)->select('slug', 'name')->withCount('packages')->orderBy('packages_count', 'desc')->take(5)->get();
        $guideRequest = User::where('role_id', 3)->whereNotNull('guide_request')->count();
        $messages = ContactMessage::where('solve', false)->count();
        return response()->json(compact('bookingQuantity', 'bookingAmount', 'package', 'tourRunning', 'todayQuantity', 'todayAmount', 'bookingChart', 'destinationPackage', 'topGuide', 'guideRequest', 'messages'));
    }

    public function adminBookingDetails()
    {
        $this->authorize('admin');
        $bookings = Booking::whereDate('created_at', '>', now()->subMonth())->with('payments')->get();
        return response()->json(compact('bookings'));
    }

    public function adminTodayBookingDetails()
    {
        $this->authorize('admin');
        $bookings = Booking::whereDate('created_at', now())->with('payments')->get();
        return response()->json(compact('bookings'));
    }

    public function adminAvailablePackage()
    {
        $this->authorize('admin');
        $packages = Package::whereDate('start_date', '>', now())->with('bookings.payments')->get();
        return response()->json(compact('packages'));
    }

    public function adminTourRunningPackage()
    {
        $this->authorize('admin');
        $runningPackages = Package::whereDate('start_date', '<=', now())->where('status', null)->with('bookings.payments')->get();
        return response()->json(compact('runningPackages'));
    }

    public function guideDashboard()
    {
        $this->authorize('guide');
        $packages = Package::where('user_id', Auth::id())->select('id')->get();
        $packageId = [];
        foreach ($packages as $package) {
            array_push($packageId, $package->id);
        };
        $bookings = Booking::whereDate('created_at', '>', now()->subMonth())->whereIn('package_id', $packageId)->with('payments')->get();
        $bookingQuantity = 0;
        $bookingAmount = 0;
        foreach ($bookings as $booking) {
            foreach ($booking->payments as $payment) {
                if (isset($payment->amount)) {
                    $bookingAmount += $payment->amount;
                }
            }
            if (isset($booking->ticket)) {
                $bookingQuantity += $booking->ticket;
            }
        };
        $todayBookings = Booking::whereDate('created_at', now())->whereIn('package_id', $packageId)->with('payments')->get();
        $todayQuantity = 0;
        $todayAmount = 0;
        foreach ($todayBookings as $booking) {
            foreach ($booking->payments as $payment) {
                if (isset($payment->amount)) {
                    $todayAmount += $payment->amount;
                }
            }
            if (isset($booking->ticket)) {
                $todayQuantity += $booking->ticket;
            }
        };
        $bookingChart = Booking::whereDate('created_at', '>', now()->subMonth())
            ->whereIn('package_id', $packageId)
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('SUM(ticket) as ticket')
            ));
        $packages = Package::where('user_id', Auth::id())->whereDate('start_date', '>', now())->with('bookings.payments')->get();
        return response()->json(compact('bookingQuantity', 'bookingAmount', 'todayQuantity', 'todayAmount', 'bookingChart', 'packages'));
    }

    public function guideBookingDetails()
    {
        $this->authorize('guide');
        $packages = Package::where('user_id', Auth::id())->select('id')->get();
        $packageId = [];
        foreach ($packages as $package) {
            array_push($packageId, $package->id);
        };
        $bookings = Booking::whereDate('created_at', '>', now()->subMonth())->whereIn('package_id', $packageId)->with('payments')->get();
        return response()->json(compact('bookings'));
    }

    public function guideTodayBookingDetails()
    {
        $this->authorize('guide');
        $packages = Package::where('user_id', Auth::id())->select('id')->get();
        $packageId = [];
        foreach ($packages as $package) {
            array_push($packageId, $package->id);
        };
        $bookings = Booking::whereDate('created_at', now())->whereIn('package_id', $packageId)->with('payments')->get();
        return response()->json(compact('bookings'));
    }


    public function userDashboard()
    {
        $this->authorize('user');
        $totalBookings = Booking::where('user_id', Auth::id())->with('payments')->get();
        $totalQuantity = 0;
        $totalAmount = 0;
        foreach ($totalBookings as $booking) {
            foreach ($booking->payments as $payment) {
                if (isset($payment->amount)) {
                    $totalAmount += $payment->amount;
                }
            }
            if (isset($booking->ticket)) {
                $totalQuantity += $booking->ticket;
            }
        };
        $monthlyBookings = Booking::where('user_id', Auth::id())->whereDate('created_at', '>', now()->subMonth())->with('payments')->get();
        $monthlyQuantity = 0;
        $monthlyAmount = 0;
        foreach ($monthlyBookings as $booking) {
            foreach ($booking->payments as $payment) {
                if (isset($payment->amount)) {
                    $monthlyAmount += $payment->amount;
                }
            }
            if (isset($booking->ticket)) {
                $monthlyQuantity += $booking->ticket;
            }
        };
        $bookingChart = Booking::where('user_id', Auth::id())
            ->whereDate('created_at', '>', now()->subMonth())
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('SUM(ticket) as ticket')
            ));

        $bookings = Booking::whereDate('created_at', '>', now()->subMonth())->where('user_id', Auth::id())->with('package', 'payments')->get();
        return response()->json(compact('totalQuantity', 'totalAmount', 'monthlyQuantity', 'monthlyAmount', 'bookingChart', 'bookings'));
    }
}
