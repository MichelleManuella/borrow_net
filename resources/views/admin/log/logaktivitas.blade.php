@extends('layouts.admin')

@section('page-title', 'Log Aktivitas')

@section('content')
    <div class="card-stat">
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

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
            <small class="text-muted">Menampilkan {{ $log->firstItem() ?? 0 }}-{{ $log->lastItem() ?? 0 }} dari
                {{ $log->total() }} aktivitas</small>
            {{ $log->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
