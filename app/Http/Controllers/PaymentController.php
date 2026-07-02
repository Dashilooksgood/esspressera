<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        if (!$booking->payment) {
            abort(404);
        }

        return view('payments.show', [
            'booking' => $booking->load('room'),
            'payment' => $booking->payment,
        ]);
    }

    public function markAsPaid(Booking $booking)
    {
        $payment = $booking->payment;

        if (!$payment) {
            return redirect()->back()->with('error', 'Reservasi ini belum memiliki data pembayaran.');
        }

        if ($payment->status === 'paid') {
            return redirect()->back()->with('success', 'Pembayaran reservasi ' . $booking->customer_name . ' sudah terverifikasi.');
        }

        $payment->update([
            'status' => 'paid',
            'method' => 'manual',
            'paid_at' => now(),
        ]);

        $booking->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Pembayaran reservasi ' . $booking->customer_name . ' telah ditandai sebagai sudah dibayar.');
    }

    public function process(Request $request, Booking $booking)
    {
        $payment = $booking->payment;

        if (!$payment || $payment->status === 'paid') {
            return redirect()->route('dashboard');
        }

        if ($payment->amount <= 0) {
            $payment->update(['status' => 'not_required']);
            $booking->update(['status' => 'confirmed']);

            return redirect()->route('dashboard')
                ->with('success', 'Reservasi ' . $booking->customer_name . ' terkonfirmasi.');
        }

        $data = $request->validate([
            'card_number' => ['required', 'string'],
            'expiry' => ['required', 'regex:/^\d{2}\/\d{2}$/'],
            'cvv' => ['required', 'regex:/^\d{3,4}$/'],
        ], [
            'card_number.required' => 'Nomor kartu wajib diisi.',
            'expiry.required' => 'Tanggal kedaluwarsa wajib diisi.',
            'expiry.regex' => 'Format tanggal kedaluwarsa harus MM/YY.',
            'cvv.required' => 'CVV wajib diisi.',
            'cvv.regex' => 'CVV harus terdiri dari 3 atau 4 digit.',
        ]);

        $digits = preg_replace('/\D/', '', $data['card_number']);

        if (strlen($digits) < 12 || !$this->luhnValid($digits)) {
            return back()->withInput()->withErrors(['card_number' => 'Nomor kartu tidak valid. Silakan periksa kembali.']);
        }

        // Re-cek ketersediaan sebelum mengonfirmasi (menghindari race condition)
        if (!Booking::isRoomAvailable($booking->room_id, $booking->date->format('Y-m-d'), $booking->time, $booking->duration, $booking->id)) {
            return back()->withErrors(['card_number' => 'Slot ini baru saja terisi oleh reservasi lain.']);
        }

        $payment->update([
            'status' => 'paid',
            'method' => 'card',
            'card_last4' => substr($digits, -4),
            'paid_at' => now(),
        ]);

        $booking->update(['status' => 'confirmed']);

        return redirect()->route('dashboard')
            ->with('success', 'Pembayaran berhasil. Reservasi ' . $booking->customer_name . ' terkonfirmasi.');
    }

    private function luhnValid(string $digits): bool
    {
        $sum = 0;
        $alt = false;
        for ($i = strlen($digits) - 1; $i >= 0; $i--) {
            $n = (int) $digits[$i];
            if ($alt) {
                $n *= 2;
                if ($n > 9) {
                    $n -= 9;
                }
            }
            $sum += $n;
            $alt = !$alt;
        }
        return $sum % 10 === 0;
    }
}
