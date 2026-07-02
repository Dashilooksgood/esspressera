<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_can_be_marked_as_paid_from_pending_status(): void
    {
        $room = Room::create([
            'name' => 'Test Room',
            'capacity' => 4,
            'deposit' => 100000,
        ]);

        $booking = Booking::create([
            'room_id' => $room->id,
            'customer_name' => 'Budi',
            'phone' => '08123456789',
            'date' => '2026-07-10',
            'time' => '19:00',
            'duration' => 60,
            'party_size' => 2,
            'status' => 'pending',
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => 100000,
            'status' => 'unpaid',
        ]);

        $response = $this->post(route('bookings.mark-paid', $booking));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pembayaran reservasi Budi telah ditandai sebagai sudah dibayar.');

        $this->assertSame('confirmed', $booking->fresh()->status);
        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertNotNull($payment->fresh()->paid_at);
    }
}
