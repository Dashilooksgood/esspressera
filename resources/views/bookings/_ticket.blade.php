@php
  $roomName = $b->room->name ?? 'Ruangan sudah dihapus';
  $payment = $b->payment;
  if ($payment && $payment->status === 'paid') {
      $priceLabel = 'Rp' . number_format($payment->amount, 0, ',', '.');
  } elseif ($payment && $payment->status === 'refunded') {
      $priceLabel = 'dana dikembalikan';
  } elseif ($payment && $payment->amount > 0) {
      $priceLabel = 'Rp' . number_format($payment->amount, 0, ',', '.') . ' belum dibayar';
  } else {
      $priceLabel = 'tanpa deposit';
  }
@endphp
<div class="ticket">
  <div class="left">
    <span class="who">{{ $b->customer_name }} &middot; rombongan {{ $b->party_size }} orang</span>
    <span class="meta">{{ $b->date->translatedFormat('d M Y') }} &middot; {{ $b->time }} &middot; {{ $b->duration }} menit</span>
    <span class="room-tag">{{ $roomName }}{{ $b->phone ? ' · ' . $b->phone : '' }}</span>
  </div>
  <div class="right">
    <span class="price">{{ $priceLabel }}</span>
    <span class="badge {{ $b->status }}">{{ $b->status === 'pending' ? 'menunggu bayar' : ($b->status === 'confirmed' ? 'terkonfirmasi' : 'dibatalkan') }}</span>
    @if ($b->status === 'pending' && $payment && $payment->status === 'unpaid')
      <form method="POST" action="{{ route('bookings.mark-paid', $b) }}" onsubmit="return confirm('Tandai pembayaran reservasi {{ $b->customer_name }} sebagai sudah dibayar?');">
        @csrf
        <button type="submit" class="btn btn-sm btn-primary">Sudah dibayar</button>
      </form>
    @endif
    @isset($showCancel)
      @if ($b->status !== 'cancelled')
        <form method="POST" action="{{ route('bookings.cancel', $b) }}" onsubmit="return confirm('Batalkan reservasi {{ $b->customer_name }}?');">
          @csrf
          <button type="submit" class="btn btn-sm btn-danger">Batalkan</button>
        </form>
      @endif
    @endisset
  </div>
</div>
