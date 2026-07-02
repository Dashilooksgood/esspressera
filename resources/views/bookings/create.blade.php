@extends('layouts.app')

@section('title', 'Reservasi baru')

@section('content')
<div class="page-head">
  <div>
    <h1>Reservasi baru</h1>
    <p>Pesan meja, kursi bar, atau salah satu ruang privat.</p>
  </div>
</div>

<form method="POST" action="{{ route('bookings.store') }}" id="booking-form">
  @csrf
  <input type="hidden" name="room_id" id="room_id" value="{{ old('room_id') }}">

  <div class="card">
    <div class="field-row3">
      <div class="field">
        <label for="date">Tanggal</label>
        <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}">
      </div>
      <div class="field">
        <label for="time">Jam</label>
        <select name="time" id="time">
          @foreach ($timeSlots as $slot)
            <option value="{{ $slot }}" @selected(old('time') === $slot)>{{ $slot }}</option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label for="duration">Durasi</label>
        <select name="duration" id="duration">
          <option value="30" @selected(old('duration') == 30)>30 menit</option>
          <option value="60" @selected(old('duration', 60) == 60)>1 jam</option>
          <option value="90" @selected(old('duration') == 90)>1.5 jam</option>
          <option value="120" @selected(old('duration') == 120)>2 jam</option>
        </select>
      </div>
    </div>
    <div class="field">
      <label for="party_size">Jumlah tamu</label>
      <input type="number" name="party_size" id="party_size" min="1" max="60" value="{{ old('party_size', 2) }}">
    </div>

    <div class="field">
      <label>Pilih Ruangan atau Meja</label>
      <div class="room-pick" id="room-pick"></div>
      <p class="helper-text" id="room-helper">Memuat ketersediaan...</p>
    </div>
  </div>

  <div class="card">
    <h2 class="section-title" style="margin-bottom:16px;">Data tamu</h2>
    <div class="field-row">
      <div class="field">
        <label for="customer_name">Nama lengkap</label>
        <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" placeholder="Nadia Putri">
      </div>
      <div class="field">
        <label for="phone">Nomor telepon</label>
        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+62 812 3456 7890">
      </div>
    </div>
    <div class="field">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="nadia@email.com">
    </div>
    <div class="field">
      <label for="notes">Catatan untuk barista (opsional)</label>
      <textarea name="notes" id="notes" rows="2" placeholder="Minta kursi dekat jendela, ada perayaan ulang tahun, alergi tertentu...">{{ old('notes') }}</textarea>
    </div>
  </div>

  <div style="display:flex;justify-content:flex-end;gap:10px;">
    <button type="submit" class="btn btn-primary">Tinjau reservasi</button>
  </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const availabilityUrl = "{{ route('bookings.availability') }}";
  const roomPick = document.getElementById('room-pick');
  const roomHelper = document.getElementById('room-helper');
  const roomIdInput = document.getElementById('room_id');
  const bookingForm = document.getElementById('booking-form');

  if (!roomPick || !roomHelper || !roomIdInput || !bookingForm) {
    return;
  }

  let selectedRoomId = roomIdInput.value || null;

  function money(n){
    return 'Rp' + Number(n).toLocaleString('id-ID');
  }

  async function refreshRooms(){
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const duration = document.getElementById('duration').value;
    const party = document.getElementById('party_size').value || 1;

    if(!date || !time){
      roomPick.innerHTML = '';
      roomHelper.textContent = 'Pilih tanggal, jam, dan jumlah tamu untuk melihat yang tersedia.';
      return;
    }

    const params = new URLSearchParams({ date, time, duration, party_size: party });
    roomHelper.textContent = 'Memuat ketersediaan...';

    try{
      const res = await fetch(availabilityUrl + '?' + params.toString(), { headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      renderRooms(data.rooms || []);
    }catch(e){
      roomHelper.textContent = 'Gagal memuat ketersediaan ruangan. Coba lagi.';
    }
  }

  function renderRooms(rooms){
    if(!rooms.length){
      roomPick.innerHTML = '';
      roomHelper.textContent = 'Belum ada ruangan/meja yang terdaftar. Tambahkan lewat menu Ruangan & meja.';
      return;
    }

    roomPick.innerHTML = rooms.map(r => {
      const selCls = (selectedRoomId == r.id && r.available) ? 'selected' : '';
      const unavailCls = r.available ? '' : 'unavailable';
      const feeHtml = r.available
        ? (r.deposit > 0 ? `<div class="rfee">${money(r.deposit)} deposit untuk menahan tempat</div>` : `<div class="rfee">Tanpa deposit</div>`)
        : `<div class="rmeta" style="margin-top:4px;color:var(--faint);">${r.reason || ''}</div>`;

      return `
        <div class="room-opt ${selCls} ${unavailCls}" data-room-id="${r.id}" role="button" tabindex="${r.available ? 0 : -1}">
          <div class="rname">${r.name}</div>
          <div class="rmeta">${r.type} &middot; muat ${r.capacity} orang</div>
          ${feeHtml}
        </div>
      `;
    }).join('');

    roomHelper.textContent = 'Pilih tempat yang sesuai dengan rombongan Anda.';

    const stillValid = rooms.some(r => r.id == selectedRoomId && r.available);
    if(!stillValid){
      selectedRoomId = null;
      roomIdInput.value = '';
    }
  }

  function selectRoom(id){
    selectedRoomId = id;
    roomIdInput.value = id;
    refreshRooms();
  }

  roomPick.addEventListener('click', function (event) {
    const option = event.target.closest('.room-opt[data-room-id]');
    if (!option || option.classList.contains('unavailable')) {
      return;
    }

    selectRoom(Number(option.dataset.roomId));
  });

  roomPick.addEventListener('keydown', function (event) {
    if (event.key !== 'Enter' && event.key !== ' ') {
      return;
    }

    const option = event.target.closest('.room-opt[data-room-id]');
    if (!option || option.classList.contains('unavailable')) {
      return;
    }

    event.preventDefault();
    selectRoom(Number(option.dataset.roomId));
  });

  ['date','time','duration','party_size'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', refreshRooms);
      el.addEventListener('change', refreshRooms);
    }
  });

  bookingForm.addEventListener('submit', function(e){
    if(!roomIdInput.value){
      e.preventDefault();
      alert('Pilih ruangan atau meja yang tersedia terlebih dahulu.');
    }
  });

  refreshRooms();
});
</script>
@endsection
