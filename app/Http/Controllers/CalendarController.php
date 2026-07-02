<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->query('tahun', now()->year);
        $month = (int) $request->query('bulan', now()->month);

        $cursor = Carbon::create($year, $month, 1);
        $startOfMonth = $cursor->copy()->startOfMonth();
        $endOfMonth = $cursor->copy()->endOfMonth();

        $counts = Booking::active()
            ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->selectRaw('date, count(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $prev = $cursor->copy()->subMonth();
        $next = $cursor->copy()->addMonth();

        return view('calendar.index', [
            'cursor' => $cursor,
            'startOffset' => $startOfMonth->dayOfWeek,
            'daysInMonth' => $cursor->daysInMonth,
            'counts' => $counts,
            'prevYear' => $prev->year,
            'prevMonth' => $prev->month,
            'nextYear' => $next->year,
            'nextMonth' => $next->month,
        ]);
    }

    public function day(string $tanggal)
    {
        $date = Carbon::parse($tanggal);

        $bookings = Booking::with('room', 'payment')
            ->active()
            ->whereDate('date', $date)
            ->orderBy('time')
            ->get();

        return view('calendar.day', [
            'date' => $date,
            'bookings' => $bookings,
        ]);
    }
}
