@extends('layouts.app')

@section('content')
    <div class="mb-4">@include('cabinet.tickets._nav')</div>

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

    {{-- Delete tugmasi --}}
    <form method="POST" action="{{ route('cabinet.tickets.destroy', $ticket) }}"
          onsubmit="return confirm('Delete this ticket?')" class="mb-3">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn"
                style="background:#dc2626;color:#fff;border-radius:8px;padding:8px 16px;">Delete</button>
    </form>

    <div class="row g-3 mb-4">
        {{-- Chap: tiket ma'lumoti --}}
        <div class="col-lg-7">
            <div class="card">
                <table class="table mb-0" style="font-size:0.9rem;">
                    <tr><th class="ps-4" style="width:160px;">ID</th><td>{{ $ticket->id }}</td></tr>
                    <tr><th class="ps-4">Created</th><td>{{ $ticket->created_at->format('Y-m-d H:i:s') }}</td></tr>
                    <tr><th class="ps-4">Updated</th><td>{{ $ticket->updated_at->format('Y-m-d H:i:s') }}</td></tr>
                    <tr><th class="ps-4">Status</th><td>@include('cabinet.tickets._status', ['status' => $ticket->status])</td></tr>
                </table>
            </div>
        </div>

        {{-- O'ng: status tarixi --}}
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

    {{-- Tiket matni --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">{{ $ticket->subject }}</h5>
            <div style="white-space:pre-line;color:#374151;">{{ $ticket->content }}</div>
        </div>
    </div>

    {{-- Xabarlar --}}
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

    {{-- Yangi xabar yuborish --}}
    <form method="POST" action="{{ route('cabinet.tickets.message', $ticket) }}" class="mt-3">
        @csrf
        <div class="mb-2">
            <textarea name="message" rows="4" class="form-control" style="border-radius:10px;"
                      placeholder="Write a message...">{{ old('message') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
@endsection
