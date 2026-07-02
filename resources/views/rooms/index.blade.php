@extends('layouts.app')

@section('title', 'Ruangan & meja')

@section('content')
<div class="page-head">
  <div>
    <h1>Ruangan &amp; meja</h1>
    <p>Kelola tempat duduk yang bisa dipesan tamu.</p>
  </div>
  <a href="{{ route('kamar.create') }}" class="btn btn-primary">+ Tambah ruangan / meja</a>
</div>

@if ($rooms->isEmpty())
  <div class="empty-state">
    <div class="title">Belum ada ruangan/meja</div>
    <div>Tambahkan meja atau ruangan pertama Anda agar tamu bisa mulai memesan.</div>
  </div>
@else
  <div class="rooms-grid">
    @foreach ($rooms as $room)
      <div class="room-card">
        <div>
          <h3>{{ $room->name }}</h3>
          <div class="rtype">{{ $room->typeLabel() }}</div>
        </div>
        <div class="rdesc">{{ $room->description ?: 'Belum ada deskripsi.' }}</div>
        <div class="rstats">
          <span>Muat {{ $room->capacity }} orang</span>
          <span>{{ $room->deposit > 0 ? 'Deposit Rp' . number_format($room->deposit, 0, ',', '.') : 'Tanpa deposit' }}</span>
          <span>{{ $room->upcoming_count }} akan datang</span>
        </div>
        <div class="ractions">
          <a href="{{ route('kamar.edit', $room) }}" class="icon-btn">Ubah</a>
          <form method="POST" action="{{ route('kamar.destroy', $room) }}" onsubmit="return confirm('Hapus {{ $room->name }}?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="icon-btn">Hapus</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
@endif
@endsection
