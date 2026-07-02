@extends('layouts.app')

@section('title', 'Kalender')

@section('content')
<div class="page-head">
  <div>
    <h1>Kalender</h1>
    <p>Semua jadwal reservasi kafe, per bulan.</p>
  </div>
</div>

<div class="card">
  <div class="cal-head">
    <a href="{{ route('calendar.index', ['tahun' => $prevYear, 'bulan' => $prevMonth]) }}" class="icon-btn">&larr;</a>
    <div class="month">{{ $cursor->translatedFormat('F Y') }}</div>
    <a href="{{ route('calendar.index', ['tahun' => $nextYear, 'bulan' => $nextMonth]) }}" class="icon-btn">&rarr;</a>
  </div>

  <div class="cal-grid">
    @foreach (['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $dow)
      <div class="cal-dow">{{ $dow }}</div>
    @endforeach
  </div>

  <div class="cal-grid" style="margin-top:6px;">
    @for ($i = 0; $i < $startOffset; $i++)
      <div class="cal-cell empty"></div>
    @endfor

    @for ($d = 1; $d <= $daysInMonth; $d++)
      @php
        $dateObj = $cursor->copy()->day($d);
        $key = $dateObj->format('Y-m-d');
        $count = $counts[$key] ?? 0;
        $isToday = $key === now()->format('Y-m-d');
      @endphp
      <a href="{{ route('calendar.day', $key) }}" class="cal-cell {{ $isToday ? 'today' : '' }}">
        <div class="dnum">{{ $d }}</div>
        @if ($count > 0)
          <div class="dcount">{{ $count }} reservasi</div>
        @endif
      </a>
    @endfor
  </div>
</div>
@endsection
