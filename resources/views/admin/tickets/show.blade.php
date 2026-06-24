@extends('layouts.app')

@section('content')
    @include('admin.tickets._nav')

    @if(session('success'))
        <div class="alert alert-success" style="border-radius:10px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Action tugmalari --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn"
           style="background:#2563eb;color:#fff;border-radius:8px;padding:8px 16px;">Edit</a>

        @if($ticket->isOpen())
            <form method="POST" action="{{ route('admin.tickets.approve', $ticket) }}">
                @csrf
                <button type="submit" class="btn"
                        style="background:#16a34a;color:#fff;border-radius:8px;padding:8px 16px;">Approve</button>
            </form>
        @endif

        @if(!$ticket->isClosed())
            <form method="POST" action="{{ route('admin.tickets.close', $ticket) }}"
                  onsubmit="return confirm('Close this ticket?')">
                @csrf
                <button type="submit" class="btn"
                        style="background:#16a34a;color:#fff;border-radius:8px;padding:8px 16px;">Close</button>
            </form>
        @endif

        <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}"
              onsubmit="return confirm('Delete this ticket?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn"
                    style="background:#dc2626;color:#fff;border-radius:8px;padding:8px 16px;">Delete</button>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card">
                <table class="table mb-0" style="font-size:0.9rem;">
                    <tr><th class="ps-4" style="width:160px;">ID</th><td>{{ $ticket->id }}</td></tr>
                    <tr><th class="ps-4">Created</th><td>{{ $ticket->created_at->format('Y-m-d H:i:s') }}</td></tr>
                    <tr><th class="ps-4">Updated</th><td>{{ $ticket->updated_at->format('Y-m-d H:i:s') }}</td></tr>
                    <tr><th class="ps-4">User</th>
                        <td><a href="{{ route('admin.users.show', $ticket->user_id) }}" style="color:#2563eb;text-decoration:none;">{{ $ticket->user?->name }}</a></td>
                    </tr>
                    <tr><th class="ps-4">Status</th><td>@include('cabinet.tickets._status', ['status' => $ticket->status])</td></tr>
                </table>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <table class="table mb-0" style="font-size:0.88rem;">
                    <thead style="background:#f8f9fb;">
                        <tr>
                            <th class="ps-4" style="font-weight:600;color:#6b7280;">Date</th>
                            <th style="font-weight:600;color:#6b7280;">User</th>
                            <th style="font-weight:600;color:#6b7280;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ticket->statuses as $status)
                            <tr>
                                <td class="ps-4">{{ $status->created_at?->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $status->user?->name }}</td>
                                <td>@include('cabinet.tickets._status', ['status' => $status->status])</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">{{ $ticket->subject }}</h5>
            <div style="white-space:pre-line;color:#374151;">{{ $ticket->content }}</div>
        </div>
    </div>

    @foreach($ticket->messages as $message)
        <div class="card mb-3">
            <div class="card-body">
                <div style="font-size:0.85rem;color:#9ca3af;margin-bottom:8px;">
                    {{ $message->created_at->format('Y-m-d H:i:s') }} by <strong style="color:#374151;">{{ $message->user?->name }}</strong>
                </div>
                <div style="white-space:pre-line;color:#374151;">{{ $message->message }}</div>
            </div>
        </div>
    @endforeach

    <form method="POST" action="{{ route('admin.tickets.message', $ticket) }}" class="mt-3">
        @csrf
        <div class="mb-2">
            <textarea name="message" rows="4" class="form-control" style="border-radius:10px;"
                      placeholder="Reply...">{{ old('message') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
@endsection
