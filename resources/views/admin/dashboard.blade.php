@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card-stat" style="background:linear-gradient(135deg,rgba(229,217,182,0.7),#fdfbf5)">
                <small>Total User</small>
                <h3>{{ $totalUser }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-stat" style="background:linear-gradient(135deg,rgba(98,129,65,0.18),#fdfbf5)">
                <small>Total Alat</small>
                <h3>{{ $totalAlat }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-stat" style="background:linear-gradient(135deg,rgba(64,81,59,0.14),#fdfbf5)">
                <small>Peminjaman Aktif</small>
                <h3>{{ $peminjamanAktif }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-stat" style="background:linear-gradient(135deg,rgba(230,126,34,0.14),#fdfbf5)">
                <small>Pengembalian Hari Ini</small>
                <h3>{{ $pengembalianHariIni }}</h3>
            </div>
        </div>
    </div>
@endsection
