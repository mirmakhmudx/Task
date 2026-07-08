@extends('layouts.app')

@section('content')
    <div class="mb-4">@include('cabinet.dialogs._nav')</div>

    @php
        $me      = auth()->id();
        $isOwner = $me === $dialog->user_id;
        $other   = $isOwner ? $dialog->client : $dialog->owner;
    @endphp

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <div style="font-size:0.8rem;color:#9ca3af;">Advert</div>
                <a href="{{ route('adverts.show', $dialog->advert_id) }}"
                   style="font-size:1.05rem;font-weight:600;color:#2563eb;text-decoration:none;">
                    {{ $dialog->advert?->title ?? 'Advert #' . $dialog->advert_id }}
                </a>
            </div>
            <div class="text-end">
                <div style="font-size:0.8rem;color:#9ca3af;">With</div>
                <div style="font-weight:600;color:#374151;">{{ $other?->name ?? '—' }}</div>
            </div>
        </div>
    </div>

    @foreach($dialog->messages as $message)
        @php $mine = $message->user_id === $me; @endphp
        <div class="d-flex mb-2 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
            <div style="max-width:70%;background:{{ $mine ? '#2563eb' : '#fff' }};color:{{ $mine ? '#fff' : '#374151' }};
                        border:1px solid {{ $mine ? '#2563eb' : '#e5e7eb' }};border-radius:14px;padding:10px 14px;">
                <div style="font-size:0.78rem;opacity:0.75;margin-bottom:4px;">
                    {{ $message->user?->name }} · {{ $message->created_at->format('Y-m-d H:i') }}
                </div>
                <div style="white-space:pre-line;">{{ $message->message }}</div>
            </div>
        </div>
    @endforeach

    <form method="POST" action="{{ route('cabinet.dialogs.message', $dialog) }}" class="mt-3">
        @csrf
        <div class="mb-2">
            <textarea name="message" rows="3" class="form-control" style="border-radius:10px;"
                      placeholder="Write a message...">{{ old('message') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
@endsection
