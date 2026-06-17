@extends('layouts.app')

@section('content')
    <h5 class="fw-bold mb-4">Bannerni tahrirlash</h5>

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cabinet.banners.update', $banner) }}">
        @csrf
        @method('PUT')

        <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;max-width:640px;">
            <div class="card-body" style="padding:20px;">
                <div class="mb-3">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">Nomi</label>
                    <input type="text" name="name" value="{{ old('name', $banner->name) }}"
                           class="form-control" style="border-radius:10px;">
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">Limit</label>
                    <input type="number" name="limit" min="1" value="{{ old('limit', $banner->limit) }}"
                           class="form-control" style="border-radius:10px;">
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">URL</label>
                    <input type="url" name="url" value="{{ old('url', $banner->url) }}"
                           class="form-control" style="border-radius:10px;">
                </div>
                <div class="mb-4">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">Format</label>
                    <select name="format" class="form-select" style="border-radius:10px;">
                        @foreach($formats as $key => $label)
                            <option value="{{ $key }}" {{ old('format', $banner->format) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn" style="background:#111827;color:#fff;border-radius:10px;font-weight:600;padding:11px 22px;">Saqlash</button>
                <a href="{{ route('cabinet.banners.show', $banner) }}" class="btn" style="background:#f3f4f6;color:#374151;border-radius:10px;padding:11px 22px;">Bekor qilish</a>
            </div>
        </div>
    </form>
@endsection
