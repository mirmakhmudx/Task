@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.banners.show', $banner) }}"
           style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid #e5e7eb;border-radius:10px;color:#6b7280;text-decoration:none;background:#fff;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <h5 class="mb-0 fw-bold">Banner rad etish — {{ $banner->name }}</h5>
    </div>

    <div class="card" style="border-radius:14px;max-width:600px;">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.banners.reject', $banner) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Rad etish sababi</label>
                    <textarea name="reason" rows="4" class="form-control" style="border-radius:10px;" required>{{ old('reason') }}</textarea>
                    @error('reason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">Rad etish</button>
                    <a href="{{ route('admin.banners.show', $banner) }}" class="btn btn-outline-secondary">Bekor qilish</a>
                </div>
            </form>
        </div>
    </div>
@endsection
