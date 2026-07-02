@php $room = $room ?? null; @endphp

<div class="card" style="max-width:520px;">
  <div class="field">
    <label for="name">Nama</label>
    <input type="text" name="name" id="name" value="{{ old('name', $room->name ?? '') }}" placeholder="Meja Taman">
  </div>
  <div class="field-row">
    <div class="field">
      <label for="type">Jenis</label>
      <select name="type" id="type">
        @foreach (\App\Models\Room::TYPES as $value => $label)
          <option value="{{ $value }}" @selected(old('type', $room->type ?? 'standard') === $value)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label for="capacity">Kapasitas</label>
      <input type="number" name="capacity" id="capacity" min="1" max="100" value="{{ old('capacity', $room->capacity ?? 4) }}">
    </div>
  </div>
  <div class="field">
    <label for="deposit">Deposit untuk memesan (Rupiah, isi 0 jika tidak perlu)</label>
    <input type="number" name="deposit" id="deposit" min="0" step="1000" value="{{ old('deposit', $room->deposit ?? 0) }}">
  </div>
  <div class="field">
    <label for="description">Deskripsi</label>
    <textarea name="description" id="description" rows="3" placeholder="Apa yang membuat tempat ini istimewa">{{ old('description', $room->description ?? '') }}</textarea>
  </div>
</div>
