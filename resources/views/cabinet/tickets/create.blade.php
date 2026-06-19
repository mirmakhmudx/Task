@extends('layouts.app')

@section('content')
    <div class="mb-4">@include('cabinet.tickets._nav')</div>

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cabinet.tickets.store') }}">
        @csrf
        <div class="card" style="max-width:760px;">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:0.85rem;">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           class="form-control" style="border-radius:10px;">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:0.85rem;">Content</label>
                    <textarea name="content" rows="7" class="form-control" style="border-radius:10px;">{{ old('content') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('cabinet.tickets.index') }}" class="btn btn-outline-primary">Cancel</a>
            </div>
        </div>
    </form>
@endsection
