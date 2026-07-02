@extends('layouts.app')

@section('title', $date->translatedFormat('d M Y'))

@section('content')
<div class="page-head">
  <div>
    <h1>{{ $date->translatedFormat('l, d F Y') }}</h1>
    <p>{{ $bookings->count() }} reservasi pada hari ini.</p>
  </div>
  <a href="{{ route('calendar.index', ['tahun' => $date->year, 'bulan' => $date->month]) }}" class="btn">&larr; Kembali ke kalender</a>
</div>

@if ($bookings->isEmpty())
  <div class="empty-state">
    <div class="title">Tidak ada reservasi di hari ini</div>
    <div>Sepanjang hari masih kosong — reservasi baru akan muncul di sini.</div>
  </div>
@else
  @foreach ($bookings as $b)
    @include('bookings._ticket', ['b' => $b])
  @endforeach
@endif
@endsection
