<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Garage;
use App\Models\Booking;
use App\Models\User;
use App\Http\Resources\DashboardResource;
use App\Http\Resources\BookingResource;
use App\Http\Resources\GarageResource;

class DashboardController extends Controller
{
    public function stats()
    {
        $data = [
            'total_garages' => Garage::count(),
            'total_bookings' => Booking::count(),
            'total_users' => User::count(),
            'recent_bookings' => Booking::with(['user', 'garage'])
                ->latest()
                ->take(5)
                ->get(),
            'active_garages' => Garage::where('is_available', '1')->count(),
            'active_bookings' => Booking::where('status', 'confirmed')->count(),
        ];

        return new DashboardResource($data);
    }
}
