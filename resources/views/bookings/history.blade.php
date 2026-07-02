@extends('layouts.app')

@section('title', 'Riwayat reservasi')

@section('content')
<div class="page-head">
  <div>
    <h1>Riwayat reservasi</h1>
    <p>Semua reservasi yang pernah dibuat, bisa dicari dan difilter.</p>
  </div>
</div>

<form method="GET" action="{{ route('bookings.history') }}" class="filter-bar">
  <input type="text" name="search" value="{{ $search }}" class="search" placeholder="Cari nama tamu, telepon, atau email">
  <button type="submit" class="btn btn-sm">Cari</button>
  <div class="filter-pill">
    <a href="{{ route('bookings.history', ['status' => 'all', 'search' => $search]) }}" class="chip {{ $status === 'all' ? 'active' : '' }}">Semua</a>
    <a href="{{ route('bookings.history', ['status' => 'confirmed', 'search' => $search]) }}" class="chip {{ $status === 'confirmed' ? 'active' : '' }}">Terkonfirmasi</a>
    <a href="{{ route('bookings.history', ['status' => 'pending', 'search' => $search]) }}" class="chip {{ $status === 'pending' ? 'active' : '' }}">Menunggu bayar</a>
    <a href="{{ route('bookings.history', ['status' => 'cancelled', 'search' => $search]) }}" class="chip {{ $status === 'cancelled' ? 'active' : '' }}">Dibatalkan</a>
  </div>
</form>

@if ($bookings->isEmpty())
  <div class="empty-state">
    <div class="title">Tidak ada reservasi yang cocok</div>
    <div>Coba kata kunci atau filter lain.</div>
  </div>
@else
  @foreach ($bookings as $b)
    @include('bookings._ticket', ['b' => $b, 'showCancel' => true])
  @endforeach

  <div style="margin-top:18px;">
    {{ $bookings->links() }}
  </div>
@endif
@endsection
