@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="page-head">
  <div>
    <h1>{{ $payment->amount > 0 ? 'Konfirmasi & bayar deposit' : 'Konfirmasi reservasi' }}</h1>
    <p>
      @if ($payment->amount > 0)
        Deposit digunakan untuk menahan tempat dari kemungkinan tidak hadir.
      @else
        Tidak perlu deposit untuk tempat ini — cukup konfirmasi datanya.
      @endif
    </p>
  </div>
</div>

<div class="card" style="max-width:440px;">
  <div class="receipt-line"><span>Tamu</span><span>{{ $booking->customer_name }}</span></div>
  <div class="receipt-line"><span>Jumlah tamu</span><span>{{ $booking->party_size }}</span></div>
  <div class="receipt-line"><span>Ruangan/meja</span><span>{{ $booking->room->name ?? '-' }}</span></div>
  <div class="receipt-line"><span>Tanggal</span><span>{{ $booking->date->translatedFormat('d M Y') }}</span></div>
  <div class="receipt-line"><span>Jam</span><span>{{ $booking->time }} ({{ $booking->duration }} menit)</span></div>
  <div class="receipt-total"><span>{{ $payment->amount > 0 ? 'Deposit yang harus dibayar' : 'Total tagihan' }}</span><span><x-rupiah :amount="$payment->amount" /></span></div>

  @if ($payment->status === 'paid')
    <div class="divider"></div>
    <p style="color:var(--sage-text);font-size:13.5px;">Pembayaran ini sudah lunas.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke dasbor</a>
  @elseif ($payment->amount > 0)
    <div class="divider"></div>
    <form method="POST" action="{{ route('payments.process', $booking) }}">
      @csrf
      <div class="field">
        <label for="card_number">Nomor kartu</label>
        <input type="text" name="card_number" id="card_number" placeholder="4242 4242 4242 4242" maxlength="19" value="{{ old('card_number') }}">
      </div>
      <div class="field-row">
        <div class="field">
          <label for="expiry">Kedaluwarsa</label>
          <input type="text" name="expiry" id="expiry" placeholder="MM/YY" maxlength="5" value="{{ old('expiry') }}">
        </div>
        <div class="field">
          <label for="cvv">CVV</label>
          <input type="text" name="cvv" id="cvv" placeholder="123" maxlength="4" value="{{ old('cvv') }}">
        </div>
      </div>
      <div class="modal-actions" style="display:flex;justify-content:flex-end;gap:10px;margin-top:10px;">
        <a href="{{ route('dashboard') }}" class="btn">Nanti saja</a>
        <button type="submit" class="btn btn-primary">Bayar <x-rupiah :amount="$payment->amount" /> &amp; konfirmasi</button>
      </div>
    </form>
  @else
    <div class="divider"></div>
    <form method="POST" action="{{ route('payments.process', $booking) }}">
      @csrf
      <button type="submit" class="btn btn-primary">Konfirmasi reservasi</button>
    </form>
  @endif
</div>
@endsection
