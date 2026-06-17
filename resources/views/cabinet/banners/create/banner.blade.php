@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Banner yaratish</h5>
        <p style="color:#9ca3af;font-size:0.85rem;margin:0;">3-qadam: Banner ma'lumotlari</p>
    </div>

    <div class="d-flex align-items-center gap-2 mb-4" style="font-size:0.82rem;color:#9ca3af;">
        <span>Kategoriya: <strong style="color:#374151;">{{ $category->name }}</strong></span>
        @if($region)
            <span>&middot;</span>
            <span>Region: <strong style="color:#374151;">{{ $region->getAddress() }}</strong></span>
        @endif
    </div>

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data"
          action="{{ $region
              ? route('cabinet.banners.create.store.region', [$category, $region])
              : route('cabinet.banners.create.store', [$category]) }}">
        @csrf

        <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:14px;max-width:640px;">
            <div class="card-body" style="padding:20px;">

                <div class="mb-3">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">Nomi (Name)</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           style="border-radius:10px;">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">Limit (ko'rsatishlar soni)</label>
                    <input type="number" name="limit" value="{{ old('limit') }}" min="1"
                           class="form-control @error('limit') is-invalid @enderror"
                           placeholder="Masalan: 1500"
                           style="border-radius:10px;">
                    @error('limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">URL (bosilganda o'tadi)</label>
                    <input type="url" name="url" value="{{ old('url') }}"
                           class="form-control @error('url') is-invalid @enderror"
                           placeholder="https://..."
                           style="border-radius:10px;">
                    @error('url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">Format</label>
                    <select name="format" class="form-select @error('format') is-invalid @enderror" style="border-radius:10px;">
                        @foreach($formats as $key => $label)
                            <option value="{{ $key }}" {{ old('format') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('format') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">Banner rasmi</label>
                    <input type="file" name="file"
                           class="form-control @error('file') is-invalid @enderror"
                           accept="image/*"
                           style="border-radius:10px;">
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn"
                        style="background:#111827;color:#fff;border-radius:10px;font-weight:600;padding:11px 22px;">
                    Banner yaratish
                </button>
                <a href="{{ route('cabinet.banners.index') }}" class="btn"
                   style="background:#f3f4f6;color:#374151;border-radius:10px;font-weight:500;padding:11px 22px;border:none;">
                    Bekor qilish
                </a>
            </div>
        </div>
    </form>
@endsection
