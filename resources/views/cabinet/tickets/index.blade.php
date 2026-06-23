@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        @include('cabinet.tickets._nav')
        <a href="{{ route('cabinet.tickets.create') }}" class="btn"
           style="background:#16a34a;color:#fff;border-radius:10px;font-weight:600;padding:9px 18px;">
            + Add Ticket
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="border-radius:10px;">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.9rem;">
                <thead style="background:#f8f9fb;border-bottom:1px solid #e8eaf0;">
                    <tr>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">ID</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Created</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Updated</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Subject</th>
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
                                <a href="{{ route('cabinet.tickets.show', $ticket) }}"
                                   style="color:#2563eb;text-decoration:none;font-weight:500;">{{ $ticket->subject }}</a>
                            </td>
                            <td style="padding:12px 16px;">@include('cabinet.tickets._status', ['status' => $ticket->status])</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4" style="color:#9ca3af;">No tickets yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $tickets->links() }}</div>
@endsection
