@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card-stat" style="background:linear-gradient(135deg,#fad3ce,#fff)">
            <small>Total User</small>
            <h3>120</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat" style="background:linear-gradient(135deg,#dceeff,#fff)">
            <small>Total Alat</small>
            <h3>45</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat" style="background:linear-gradient(135deg,#ede7f6,#fff)">
            <small>Peminjaman Aktif</small>
            <h3>18</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-stat">
            <small>Pengembalian Hari Ini</small>
            <h3>7</h3>
        </div>
    </div>
</div>
@endsection
