@extends('layouts.app')

@section('content')
    <h5 class="fw-bold mb-3">{{ $banner->name }}</h5>

    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('cabinet.banners.edit', $banner) }}" class="btn"
           style="background:#2563eb;color:#fff;border-radius:8px;padding:8px 16px;">Edit</a>

        <a href="{{ route('cabinet.banners.file', $banner) }}" class="btn"
           style="background:#0ea5e9;color:#fff;border-radius:8px;padding:8px 16px;">Change File</a>

        @if($banner->isDraft())
            <form method="POST" action="{{ route('cabinet.banners.send-to-moderation', $banner) }}"
                  onsubmit="return confirm('Send to moderation?')">
                @csrf
                <button type="submit" class="btn"
                        style="background:#16a34a;color:#fff;border-radius:8px;padding:8px 16px;">Send to Moderation</button>
            </form>
        @endif

        <form method="POST" action="{{ route('cabinet.banners.destroy', $banner) }}"
              onsubmit="return confirm('Delete?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn"
                    style="background:#dc2626;color:#fff;border-radius:8px;padding:8px 16px;">Delete</button>
        </form>
    </div>

    @if($banner->reject_reason)
        <div class="alert alert-warning" style="border-radius:10px;">
            Rad etilgan. Sababi: {{ $banner->reject_reason }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success" style="border-radius:10px;">{{ session('success') }}</div>
    @endif

    <div class="card mb-4" style="border:1px solid #e5e7eb;border-radius:14px;max-width:720px;">
        <table class="table mb-0" style="font-size:0.9rem;">
            <tr><th class="ps-4" style="width:180px;">ID</th><td>{{ $banner->id }}</td></tr>
            <tr><th class="ps-4">Nomi</th><td>{{ $banner->name }}</td></tr>
            <tr><th class="ps-4">Region</th><td>{{ $banner->region?->getAddress() ?? 'Barcha regionlar' }}</td></tr>
            <tr><th class="ps-4">Kategoriya</th><td>{{ $banner->category->name }}</td></tr>
            <tr><th class="ps-4">Holati</th><td>{{ $banner->status }}</td></tr>
            <tr><th class="ps-4">URL</th><td><a href="{{ $banner->url }}" target="_blank">{{ $banner->url }}</a></td></tr>
            <tr><th class="ps-4">Limit</th><td>{{ $banner->limit }}</td></tr>
            <tr><th class="ps-4">Ko'rsatildi (Views)</th><td>{{ $banner->views }}</td></tr>
            <tr><th class="ps-4">Format</th><td>{{ $banner->format }}</td></tr>
            <tr><th class="ps-4">Chop etilgan</th><td>{{ $banner->published_at?->format('d.m.Y H:i') ?? '—' }}</td></tr>
        </table>
    </div>

    @if($banner->file)
        @php
            $parts = explode('x', $banner->format);
            $w = $parts[0] ?? null;
            $h = $parts[1] ?? null;
        @endphp
        <div style="border:1px solid #e5e7eb;border-radius:14px;padding:20px;display:inline-block;">
            <img src="{{ Storage::url($banner->file) }}" alt="{{ $banner->name }}"
                 width="{{ $w }}" height="{{ $h }}"
                 style="object-fit:cover;display:block;border-radius:6px;">
            <p style="font-size:0.78rem;color:#9ca3af;margin:8px 0 0;">Ko'rsatish o'lchami: {{ $banner->format }}</p>
        </div>
    @endif
@endsection
