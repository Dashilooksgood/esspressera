<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount([
            'bookings as upcoming_count' => function ($q) {
                $q->active()->whereDate('date', '>=', Carbon::today());
            },
        ])->orderBy('name')->get();

        return view('rooms.index', ['rooms' => $rooms]);
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRoom($request);
        Room::create($data);

        return redirect()->route('kamar.index')->with('success', 'Ruangan/meja berhasil ditambahkan.');
    }

    public function edit(Room $kamar)
    {
        return view('rooms.edit', ['room' => $kamar]);
    }

    public function update(Request $request, Room $kamar)
    {
        $data = $this->validateRoom($request);
        $kamar->update($data);

        return redirect()->route('kamar.index')->with('success', 'Ruangan/meja berhasil diperbarui.');
    }

    public function destroy(Room $kamar)
    {
        $hasUpcoming = $kamar->bookings()->active()->whereDate('date', '>=', Carbon::today())->exists();

        $kamar->delete();

        $message = $hasUpcoming
            ? 'Ruangan/meja dihapus. Reservasi yang sudah ada tetap tersimpan sebagai riwayat.'
            : 'Ruangan/meja berhasil dihapus.';

        return redirect()->route('kamar.index')->with('success', $message);
    }

    private function validateRoom(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:standard,komunal,privat,outdoor'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'deposit' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Nama ruangan/meja wajib diisi.',
            'capacity.required' => 'Kapasitas wajib diisi.',
            'capacity.min' => 'Kapasitas minimal 1 orang.',
        ]);
    }
}
