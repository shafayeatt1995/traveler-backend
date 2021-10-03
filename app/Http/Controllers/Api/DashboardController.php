<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Place;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $bookings = Booking::whereDate('created_at', '>', now()->subMonth())->with('payments')->get();
        $package = Package::whereDate('start_date', '>', now())->count();
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
        $bookingChart = Booking::where('created_at', '>=', Carbon::now()->subMonth())
        ->groupBy('date')
        ->orderBy('date', 'DESC')
        ->get(array(
            DB::raw('Date(created_at) as date'),
            DB::raw('SUM(ticket) as ticket')
        ));
        $destinationPackage = Place::select('name')->withCount('runningPackages')->get();
        $topGuide = User::where('role_id', 2)->select('slug', 'name')->withCount('packages')->orderBy('packages_count', 'desc')->take(5)->get();
        return response()->json(compact('bookingQuantity', 'bookingAmount', 'package', 'bookingChart', 'destinationPackage', 'topGuide'));
    }

    public function adminBookingDetails()
    {
        $this->authorize('admin');
        $bookings = Booking::whereDate('created_at', '>', now()->subYear())->with('payments')->get();
        return response()->json(compact('bookings'));
    }

    public function adminAvailablePackage()
    {
        $this->authorize('admin');
        $packages = Package::whereDate('start_date', '>', now())->with('bookings.payments')->get();
        return response()->json(compact('packages'));
    }
}
