@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Log Aktivitas</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Aktivitas</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($log as $item)
            <tr>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>{{ $item->aktivitas }}</td>
                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Belum ada aktivitas</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
