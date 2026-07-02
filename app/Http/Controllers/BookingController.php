<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function create()
    {
        $rooms = Room::orderBy('capacity')->get();
        // @dd($rooms);
        $timeSlots = $this->buildTimeSlots();

        return view('bookings.create', [
            'rooms' => $rooms,
            'timeSlots' => $timeSlots,
        ]);
    }

    /**
     * Endpoint AJAX: cek ketersediaan kamar/meja untuk tanggal, jam, durasi, jumlah tamu tertentu.
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'duration' => ['required', 'integer', 'min:30'],
            'party_size' => ['required', 'integer', 'min:1'],
        ]);

        $rooms = Room::orderBy('capacity')->get();

        $result = $rooms->map(function (Room $room) use ($data) {
            $fits = $room->capacity >= $data['party_size'];
            $free = Booking::isRoomAvailable($room->id, $data['date'], $data['time'], (int) $data['duration']);
            $available = $fits && $free;

            $reason = null;
            if (!$fits) {
                $reason = 'Muat ' . $room->capacity . ' orang — terlalu kecil untuk rombongan Anda';
            } elseif (!$free) {
                $reason = 'Sudah dipesan pada jam ini';
            }

            return [
                'id' => $room->id,
                'name' => $room->name,
                'type' => $room->typeLabel(),
                'capacity' => $room->capacity,
                'deposit' => $room->deposit,
                'available' => $available,
                'reason' => $reason,
            ];
        });

        return response()->json(['rooms' => $result]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'duration' => ['required', 'integer', 'min:30'],
            'party_size' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'room_id.required' => 'Pilih ruangan atau meja terlebih dahulu.',
            'room_id.exists' => 'Ruangan atau meja yang dipilih tidak ditemukan.',
            'date.required' => 'Tanggal wajib diisi.',
            'time.required' => 'Jam wajib diisi.',
            'party_size.required' => 'Jumlah tamu wajib diisi.',
            'customer_name.required' => 'Nama tamu wajib diisi.',
        ]);

        if (empty($data['phone']) && empty($data['email'])) {
            return back()->withInput()->withErrors([
                'phone' => 'Isi nomor telepon atau email agar kami bisa menghubungi tamu.',
            ]);
        }

        $room = Room::findOrFail($data['room_id']);

        if ($room->capacity < $data['party_size']) {
            return back()->withInput()->withErrors(['room_id' => 'Ruangan/meja ini tidak muat untuk jumlah tamu tersebut.']);
        }

        if (!Booking::isRoomAvailable($room->id, $data['date'], $data['time'], (int) $data['duration'])) {
            return back()->withInput()->withErrors(['room_id' => 'Slot ini baru saja terisi. Silakan pilih jam atau ruangan lain.']);
        }

        $booking = Booking::create([
            'room_id' => $room->id,
            'customer_name' => $data['customer_name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'date' => $data['date'],
            'time' => $data['time'],
            'duration' => $data['duration'],
            'party_size' => $data['party_size'],
            'notes' => $data['notes'] ?? null,
            'status' => $room->deposit > 0 ? 'pending' : 'confirmed',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $room->deposit,
            'status' => $room->deposit > 0 ? 'unpaid' : 'not_required',
        ]);

        if ($room->deposit > 0) {
            return redirect()->route('payments.show', $booking)
                ->with('success', 'Reservasi dibuat. Selesaikan pembayaran deposit untuk mengonfirmasi.');
        }

        return redirect()->route('dashboard')
            ->with('success', 'Reservasi untuk ' . $booking->customer_name . ' berhasil dikonfirmasi.');
    }

    public function history(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = Booking::with('room', 'payment')
            ->orderByDesc('date')
            ->orderByDesc('time');

        if (in_array($status, ['pending', 'confirmed', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('bookings.history', [
            'bookings' => $bookings,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function cancel(Booking $booking)
    {
        $booking->status = 'cancelled';
        $booking->save();

        if ($booking->payment && $booking->payment->status === 'paid') {
            $booking->payment->status = 'refunded';
            $booking->payment->save();
        }

        return back()->with('success', 'Reservasi ' . $booking->customer_name . ' telah dibatalkan.');
    }

    private function buildTimeSlots(): array
    {
        $slots = [];
        for ($mins = 7 * 60; $mins <= 20 * 60 + 30; $mins += 30) {
            $h = intdiv($mins, 60);
            $m = $mins % 60;
            $slots[] = sprintf('%02d:%02d', $h, $m);
        }
        return $slots;
    }
}
