<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Reservasi (booking)
Route::get('/reservasi/baru', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/reservasi', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/reservasi/cek-ketersediaan', [BookingController::class, 'checkAvailability'])->name('bookings.availability');
Route::get('/riwayat', [BookingController::class, 'history'])->name('bookings.history');
Route::post('/reservasi/{booking}/batal', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::post('/reservasi/{booking}/sudah-dibayar', [PaymentController::class, 'markAsPaid'])->name('bookings.mark-paid');

// Pembayaran (payment)
Route::get('/reservasi/{booking}/bayar', [PaymentController::class, 'show'])->name('payments.show');
Route::post('/reservasi/{booking}/bayar', [PaymentController::class, 'process'])->name('payments.process');

// Kalender (calendar)
Route::get('/kalender', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/kalender/{tanggal}', [CalendarController::class, 'day'])->name('calendar.day');

// Kamar & meja (room management)
Route::resource('kamar', RoomController::class)->except(['show']);
