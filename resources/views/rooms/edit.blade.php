@extends('layouts.app')

@section('title', 'Ubah ruangan / meja')

@section('content')
<div class="page-head">
  <div>
    <h1>Ubah ruangan / meja</h1>
    <p>Perbarui detail {{ $room->name }}.</p>
  </div>
</div>

<form method="POST" action="{{ route('kamar.update', $room) }}">
  @csrf
  @method('PUT')
  @include('rooms._form', ['room' => $room])
  <div style="display:flex;justify-content:flex-end;gap:10px;max-width:520px;">
    <a href="{{ route('kamar.index') }}" class="btn">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan perubahan</button>
  </div>
</form>
@endsection
