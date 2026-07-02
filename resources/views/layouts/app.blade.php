<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Esspressera — @yield('title', 'Sistem Reservasi Kafe')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Work+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #231710; --surface: #2E2119; --surface-raised: #362719; --surface-hover: #3E2C1C;
    --line: #4A3A29; --line-soft: #3A2B1D; --cream: #F3E9D8; --muted: #B9A68C; --faint: #8A7A65;
    --brass: #C0864A; --brass-dim: #8F6435; --brass-text: #E7B47B;
    --sage: #7A8B6F; --sage-text: #A9BC9B; --cherry: #B0523F; --cherry-text: #E29584;
    --font-display: 'Fraunces', serif; --font-body: 'Work Sans', sans-serif; --font-mono: 'IBM Plex Mono', monospace;
  }
  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{background:var(--bg);color:var(--cream);font-family:var(--font-body);min-height:100vh;-webkit-font-smoothing:antialiased;}
  a{color:var(--brass-text);}
  #app{display:flex;min-height:100vh;}
  .sidebar{width:230px;flex-shrink:0;background:var(--surface);border-right:1px solid var(--line-soft);display:flex;flex-direction:column;padding:22px 16px;}
  .brand{display:flex;align-items:baseline;gap:8px;padding:0 8px 22px 8px;border-bottom:1px solid var(--line-soft);margin-bottom:18px;}
  .brand .mark{font-family:var(--font-display);font-style:italic;font-weight:500;font-size:15px;color:var(--brass-text);border:1px solid var(--brass-dim);border-radius:50%;width:30px;height:30px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .brand .name{font-family:var(--font-display);font-size:20px;font-weight:500;}
  .brand .sub{display:block;font-size:10.5px;letter-spacing:1.5px;text-transform:uppercase;color:var(--faint);margin-top:2px;}
  nav{display:flex;flex-direction:column;gap:2px;}
  .nav-btn{display:flex;align-items:center;gap:11px;color:var(--muted);font-size:14px;padding:10px 10px;border-radius:8px;text-decoration:none;transition:background .12s ease,color .12s ease;}
  .nav-btn:hover{background:var(--surface-hover);color:var(--cream);}
  .nav-btn.active{background:var(--surface-hover);color:var(--brass-text);}
  .nav-btn .ico{width:17px;height:17px;flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;}
  .nav-btn .ico svg{width:100%;height:100%;}
  .sidebar-foot{margin-top:auto;padding-top:16px;border-top:1px solid var(--line-soft);font-size:11px;color:var(--faint);line-height:1.5;}
  .main{flex:1;padding:32px 40px 60px 40px;max-width:1180px;}
  .page-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:26px;flex-wrap:wrap;gap:12px;}
  .page-head h1{font-family:var(--font-display);font-weight:500;font-size:27px;margin:0 0 4px 0;}
  .page-head p{margin:0;color:var(--muted);font-size:13.5px;}
  .btn{font-family:var(--font-body);font-size:13.5px;font-weight:500;border-radius:8px;border:1px solid var(--line);background:var(--surface-raised);color:var(--cream);padding:9px 16px;cursor:pointer;text-decoration:none;display:inline-block;transition:border-color .12s ease,background .12s ease;}
  .btn:hover{border-color:var(--faint);}
  .btn-primary{background:var(--brass);border-color:var(--brass);color:#2A1B0C;font-weight:600;}
  .btn-primary:hover{background:#CE9558;border-color:#CE9558;}
  .btn-danger{background:transparent;border-color:var(--cherry);color:var(--cherry-text);}
  .btn-danger:hover{background:rgba(176,82,63,0.14);}
  .btn-sm{padding:6px 11px;font-size:12.5px;border-radius:7px;}
  .stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:30px;}
  .stat-card{background:var(--surface);border:1px solid var(--line-soft);border-radius:12px;padding:16px 18px;}
  .stat-card .label{font-size:11.5px;letter-spacing:0.5px;text-transform:uppercase;color:var(--faint);margin-bottom:8px;}
  .stat-card .value{font-family:var(--font-display);font-size:26px;font-weight:500;color:var(--cream);}
  .stat-card .value .unit{font-size:14px;color:var(--muted);font-family:var(--font-body);}
  .section-title{font-family:var(--font-display);font-size:17px;font-weight:500;margin:0 0 12px 0;}
  .ticket{position:relative;background:var(--surface);border:1px dashed var(--line);border-radius:8px;padding:14px 16px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;}
  .ticket .left{display:flex;flex-direction:column;gap:3px;min-width:180px;}
  .ticket .who{font-weight:600;font-size:14.5px;color:var(--cream);}
  .ticket .meta{font-size:12.5px;color:var(--muted);font-family:var(--font-mono);}
  .ticket .room-tag{font-size:12px;color:var(--faint);}
  .ticket .right{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
  .price{font-family:var(--font-mono);font-size:13.5px;color:var(--cream);}
  .badge{font-size:11px;font-weight:600;letter-spacing:0.3px;padding:3px 9px;border-radius:20px;text-transform:uppercase;white-space:nowrap;}
  .badge.confirmed{background:rgba(122,139,111,0.18);color:var(--sage-text);}
  .badge.pending{background:rgba(192,134,74,0.18);color:var(--brass-text);}
  .badge.cancelled{background:rgba(176,82,63,0.18);color:var(--cherry-text);}
  .empty-state{border:1px dashed var(--line);border-radius:10px;padding:34px 20px;text-align:center;color:var(--muted);}
  .empty-state .title{font-family:var(--font-display);font-size:16px;color:var(--cream);margin-bottom:4px;}
  label{display:block;font-size:12.5px;color:var(--muted);margin-bottom:6px;}
  input, select, textarea{width:100%;background:var(--surface-raised);border:1px solid var(--line);border-radius:8px;color:var(--cream);font-family:var(--font-body);font-size:14px;padding:9px 11px;outline:none;}
  input:focus, select:focus, textarea:focus{border-color:var(--brass-dim);}
  .field{margin-bottom:15px;}
  .field-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
  .field-row3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
  .card{background:var(--surface);border:1px solid var(--line-soft);border-radius:12px;padding:22px 24px;margin-bottom:16px;}
  .room-pick{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px;margin-top:6px;}
  .room-opt{border:1px solid var(--line);border-radius:9px;padding:12px 13px;cursor:pointer;background:var(--surface-raised);transition:border-color .12s ease,background .12s ease;}
  .room-opt:hover{border-color:var(--faint);}
  .room-opt.selected{border-color:var(--brass);background:rgba(192,134,74,0.10);}
  .room-opt.unavailable{opacity:0.35;cursor:not-allowed;}
  .room-opt .rname{font-weight:600;font-size:14px;margin-bottom:3px;}
  .room-opt .rmeta{font-size:12px;color:var(--muted);}
  .room-opt .rfee{font-family:var(--font-mono);font-size:12px;color:var(--brass-text);margin-top:4px;}
  .cal-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
  .cal-head .month{font-family:var(--font-display);font-size:19px;}
  .cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:6px;}
  .cal-dow{font-size:11px;color:var(--faint);text-align:center;padding-bottom:4px;text-transform:uppercase;letter-spacing:0.5px;}
  .cal-cell{min-height:74px;border:1px solid var(--line-soft);border-radius:8px;padding:7px;text-decoration:none;display:block;background:var(--surface);transition:border-color .12s ease;}
  .cal-cell:hover{border-color:var(--faint);}
  .cal-cell.empty{background:transparent;border:none;}
  .cal-cell.today{border-color:var(--brass-dim);}
  .cal-cell .dnum{font-size:12.5px;color:var(--muted);font-family:var(--font-mono);}
  .cal-cell .dcount{margin-top:6px;font-size:11px;color:var(--brass-text);background:rgba(192,134,74,0.14);display:inline-block;padding:1px 7px;border-radius:10px;}
  .rooms-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px;}
  .room-card{background:var(--surface);border:1px solid var(--line-soft);border-radius:12px;padding:16px 18px;}
  .room-card h3{font-family:var(--font-display);font-size:16.5px;font-weight:500;margin:0;}
  .room-card .rtype{font-size:11px;color:var(--faint);text-transform:uppercase;letter-spacing:0.4px;margin-top:2px;}
  .room-card .rdesc{font-size:13px;color:var(--muted);line-height:1.5;margin:8px 0;}
  .room-card .rstats{display:flex;gap:14px;font-size:12.5px;color:var(--muted);margin-bottom:10px;flex-wrap:wrap;}
  .room-card .ractions{display:flex;gap:8px;}
  .icon-btn{background:none;border:1px solid var(--line);color:var(--muted);border-radius:7px;padding:5px 9px;font-size:12px;cursor:pointer;text-decoration:none;}
  .icon-btn:hover{border-color:var(--faint);color:var(--cream);}
  .filter-bar{display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;align-items:center;}
  .filter-bar input, .filter-bar select{width:auto;}
  .filter-bar .search{flex:1;min-width:180px;}
  .chip{border:1px solid var(--line);background:var(--surface-raised);color:var(--muted);padding:6px 12px;border-radius:20px;font-size:12.5px;cursor:pointer;text-decoration:none;display:inline-block;}
  .chip.active{border-color:var(--brass);color:var(--brass-text);background:rgba(192,134,74,0.10);}
  .receipt-line{display:flex;justify-content:space-between;font-size:13.5px;padding:6px 0;border-bottom:1px dashed var(--line);font-family:var(--font-mono);}
  .receipt-line:last-child{border-bottom:none;}
  .receipt-total{display:flex;justify-content:space-between;font-size:16px;font-weight:600;padding-top:10px;margin-top:4px;border-top:1px solid var(--line);color:var(--brass-text);}
  .alert{border-radius:8px;padding:11px 16px;font-size:13.5px;margin-bottom:16px;border:1px solid var(--line);}
  .alert-success{border-left:3px solid var(--sage);background:rgba(122,139,111,0.10);}
  .alert-error{border-left:3px solid var(--cherry);background:rgba(176,82,63,0.10);}
  .helper-text{font-size:12px;color:var(--faint);margin-top:4px;}
  nav[role="navigation"]{margin-top:6px;font-size:13px;}
  nav[role="navigation"] a, nav[role="navigation"] span{color:var(--muted);text-decoration:none;padding:4px 8px;}
  nav[role="navigation"] a:hover{color:var(--brass-text);}
  .divider{height:1px;background:var(--line-soft);margin:20px 0;}
  @media(max-width:820px){
    #app{flex-direction:column;}
    .sidebar{width:100%;flex-direction:row;align-items:center;overflow-x:auto;padding:14px 16px;}
    .brand{display:none;}
    nav{flex-direction:row;}
    .sidebar-foot{display:none;}
    .main{padding:22px 18px 60px 18px;}
    .field-row, .field-row3{grid-template-columns:1fr;}
  }
</style>
</head>
<body>
<div id="app">
  <aside class="sidebar">
    <div class="brand">
      <span class="mark">e</span>
      <div>
        <span class="name">Esspressera</span>
        <span class="sub">Reservasi &amp; meja kafe</span>
      </div>
    </div>
    <nav>
      <a href="{{ route('dashboard') }}" class="nav-btn {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="ico"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2.5" y="2.5" width="6" height="6" rx="1.2"/><rect x="11.5" y="2.5" width="6" height="6" rx="1.2"/><rect x="2.5" y="11.5" width="6" height="6" rx="1.2"/><rect x="11.5" y="11.5" width="6" height="6" rx="1.2"/></svg></span>
        Dasbor
      </a>
      <a href="{{ route('bookings.create') }}" class="nav-btn {{ request()->routeIs('bookings.create') || request()->routeIs('bookings.store') ? 'active' : '' }}">
        <span class="ico"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10 3v14M3 10h14"/></svg></span>
        Reservasi baru
      </a>
      <a href="{{ route('calendar.index') }}" class="nav-btn {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
        <span class="ico"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2.5" y="3.5" width="15" height="14" rx="1.5"/><path d="M2.5 8h15M6 2v3M14 2v3"/></svg></span>
        Kalender
      </a>
      <a href="{{ route('kamar.index') }}" class="nav-btn {{ request()->routeIs('kamar.*') ? 'active' : '' }}">
        <span class="ico"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 17V8.5L10 3l7 5.5V17"/><path d="M8 17v-6h4v6"/></svg></span>
        Ruangan &amp; meja
      </a>
      <a href="{{ route('bookings.history') }}" class="nav-btn {{ request()->routeIs('bookings.history') ? 'active' : '' }}">
        <span class="ico"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="10" cy="10" r="7"/><path d="M10 6v4l3 2"/></svg></span>
        Riwayat reservasi
      </a>
    </nav>
    <div class="sidebar-foot">Esspressera<br>Meja resepsionis kafe</div>
  </aside>

  <main class="main">
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
      <div class="alert alert-error">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    @yield('content')
  </main>
</div>
</body>
</html>
