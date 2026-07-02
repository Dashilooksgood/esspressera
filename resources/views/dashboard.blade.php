@extends('layouts.app')

@section('title', 'Dasbor')

@section('content')
<div class="page-head">
  <div>
    <h1>Senang bertemu Anda</h1>
    <p>Begini kondisi lantai kafe hari ini — {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
  </div>
  <a href="{{ route('bookings.create') }}" class="btn btn-primary">+ Reservasi baru</a>
</div>

<div class="stat-grid">
  <div class="stat-card"><div class="label">Reservasi hari ini</div><div class="value">{{ $todaysBookings->count() }}</div></div>
  <div class="stat-card"><div class="label">Minggu ini (mendatang)</div><div class="value">{{ $weekBookings->count() }}</div></div>
  <div class="stat-card"><div class="label">Deposit terkumpul</div><div class="value"><x-rupiah :amount="$revenue" /></div></div>
  <div class="stat-card"><div class="label">Okupansi hari ini</div><div class="value">{{ $occupancy }}<span class="unit"> %</span></div></div>
</div>

<h2 class="section-title">Reservasi hari ini</h2>
@if ($todaysBookings->isEmpty())
  <div class="empty-state">
    <div class="title">Belum ada reservasi hari ini</div>
    <div>Reservasi yang dibuat untuk hari ini akan muncul di sini.</div>
  </div>
@else
  @foreach ($todaysBookings as $b)
    @include('bookings._ticket', ['b' => $b])
  @endforeach
@endif

<h2 class="section-title" style="margin-top:26px;">Mendatang minggu ini</h2>
@if ($weekBookings->isEmpty())
  <div class="empty-state">
    <div class="title">Belum ada jadwal minggu ini</div>
    <div>Reservasi untuk 7 hari ke depan akan muncul di sini.</div>
  </div>
@else
  @foreach ($weekBookings as $b)
    @include('bookings._ticket', ['b' => $b])
  @endforeach
@endif
@endsection
