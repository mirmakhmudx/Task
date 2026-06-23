@extends('layouts.app')

@section('content')
    @include('admin.tickets._nav')

    @if(session('success'))
        <div class="alert alert-success" style="border-radius:10px;">{{ session('success') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3" style="color:#6b7280;">Filter</h6>
            <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.82rem;color:#6b7280;">ID</label>
                    <input type="text" name="id" value="{{ request('id') }}" class="form-control" style="border-radius:10px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:0.82rem;color:#6b7280;">User</label>
                    <input type="text" name="user" value="{{ request('user') }}" class="form-control" style="border-radius:10px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:0.82rem;color:#6b7280;">Status</label>
                    <select name="status" class="form-select" style="border-radius:10px;">
                        <option value="">— All —</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-primary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.9rem;">
                <thead style="background:#f8f9fb;border-bottom:1px solid #e8eaf0;">
                    <tr>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">ID</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Created</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Updated</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Subject</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">User</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr style="border-bottom:1px solid #f0f1f5;">
                            <td style="padding:12px 16px;">{{ $ticket->id }}</td>
                            <td style="padding:12px 16px;color:#6b7280;">{{ $ticket->created_at->format('Y-m-d H:i:s') }}</td>
                            <td style="padding:12px 16px;color:#6b7280;">{{ $ticket->updated_at->format('Y-m-d H:i:s') }}</td>
                            <td style="padding:12px 16px;">
                                <a href="{{ route('admin.tickets.show', $ticket) }}"
                                   style="color:#2563eb;text-decoration:none;font-weight:500;">{{ $ticket->subject }}</a>
                            </td>
                            <td style="padding:12px 16px;color:#374151;">{{ $ticket->user_id }} - {{ $ticket->user?->name }}</td>
                            <td style="padding:12px 16px;">@include('cabinet.tickets._status', ['status' => $ticket->status])</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4" style="color:#9ca3af;">No tickets found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $tickets->links() }}</div>
@endsection
