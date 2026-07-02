@extends('layouts.app')

@section('title', 'Tambah ruangan / meja')

@section('content')
<div class="page-head">
  <div>
    <h1>Tambah ruangan / meja</h1>
    <p>Tentukan apa yang akan dilihat tamu saat memesan.</p>
  </div>
</div>

<form method="POST" action="{{ route('kamar.store') }}">
  @csrf
  @include('rooms._form')
  <div style="display:flex;justify-content:flex-end;gap:10px;max-width:520px;">
    <a href="{{ route('kamar.index') }}" class="btn">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan ruangan</button>
  </div>
</form>
@endsection
