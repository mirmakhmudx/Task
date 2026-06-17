@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Banner yaratish</h5>
        <p style="color:#9ca3af;font-size:0.85rem;margin:0;">1-qadam: Kategoriyani tanlang</p>
    </div>

    <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;">
        <div class="card-body p-4">
            @include('cabinet.banners.create._categories', ['categories' => $categories, 'depth' => 0])
        </div>
    </div>
@endsection
