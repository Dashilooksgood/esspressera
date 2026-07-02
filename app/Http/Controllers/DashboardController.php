<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekEnd = $today->copy()->addDays(7);

        $todaysBookings = Booking::with('room', 'payment')
            ->active()
            ->whereDate('date', $today)
            ->orderBy('time')
            ->get();

        $weekBookings = Booking::with('room', 'payment')
            ->active()
            ->whereDate('date', '>', $today)
            ->whereDate('date', '<=', $weekEnd)
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $revenue = \App\Models\Payment::where('status', 'paid')->sum('amount');

        $roomCount = Room::count();
        $occupiedToday = $todaysBookings->pluck('room_id')->unique()->count();
        $occupancy = $roomCount > 0 ? round(($occupiedToday / $roomCount) * 100) : 0;

        return view('dashboard', [
            'todaysBookings' => $todaysBookings,
            'weekBookings' => $weekBookings->take(8),
            'revenue' => $revenue,
            'occupancy' => $occupancy,
        ]);
    }
}
