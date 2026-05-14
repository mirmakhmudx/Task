@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h5 class="fw-bold mb-1" style="color:#111827;letter-spacing:-0.3px;">E'lon yaratish</h5>
        <p style="color:#9ca3af;font-size:0.85rem;margin:0;">1-qadam: Kategoriyani tanlang</p>
    </div>

    {{-- Progress --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <div style="width:28px;height:28px;border-radius:50%;background:#111827;color:#fff;font-size:0.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;">1</div>
        <div style="height:2px;width:60px;background:#e5e7eb;border-radius:2px;">
            <div style="height:100%;width:0%;background:#111827;border-radius:2px;"></div>
        </div>
        <div style="width:28px;height:28px;border-radius:50%;background:#f3f4f6;color:#9ca3af;font-size:0.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;">2</div>
        <div style="height:2px;width:60px;background:#e5e7eb;border-radius:2px;"></div>
        <div style="width:28px;height:28px;border-radius:50%;background:#f3f4f6;color:#9ca3af;font-size:0.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;">3</div>
    </div>

    <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div class="card-body p-4">
            @include('cabinet.adverts.create._categories', ['categories' => $categories, 'depth' => 0])
        </div>
    </div>

@endsection
